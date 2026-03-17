<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\Commande;
use App\Models\Adresse;
use App\Models\Appartient; // lignes de commande
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeController extends Controller
{
    /**
     * Liste des commandes de l'utilisateur connecté.
     */
    public function index()
    {
        $commandes = Commande::with([
                'adresseLivraison',
                'adresseFacturation',
                'lignes.puzzle.categorie',
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('commandes.index', compact('commandes'));
    }

    /**
     * Formulaire de création (choix adresses + récap panier).
     * Redirige proprement si panier vide/introuvable ou sans adresse.
     */
    public function create()
    {
        $panier = Panier::where('user_id', Auth::id())
            ->where('statut', 0)
            ->with('lignes.puzzle')
            ->first();

        if (!$panier || $panier->lignes->isEmpty()) {
            return redirect()->route('panier.index')
                ->with('error', "Votre panier est vide ou introuvable. Ajoutez des articles avant de passer commande.");
        }

        $adresses = Adresse::where('user_id', Auth::id())->get();
        if ($adresses->isEmpty()) {
            return redirect()
                ->route('adresses.create', ['next' => route('commandes.create')])
                ->with('message', 'Ajoutez une adresse pour continuer la commande.');
        }

        // Règle: reprendre l’adresse de la toute première commande si elle existe
        $firstCommande = Commande::where('user_id', Auth::id())
            ->orderBy('date_commande', 'asc')
            ->first();

        $defaultLivraisonId   = old('adresse_livraison_id');
        $defaultFacturationId = old('adresse_facturation_id');

        if (!$defaultLivraisonId || !$defaultFacturationId) {
            if ($firstCommande) {
                $defaultLivraisonId   = $defaultLivraisonId   ?: $firstCommande->adresse_livraison_id;
                $defaultFacturationId = $defaultFacturationId ?: $firstCommande->adresse_facturation_id;
            } else {
                $defaultLivraisonId   = $defaultLivraisonId   ?: $adresses->first()->id;
                $defaultFacturationId = $defaultFacturationId ?: $adresses->first()->id;
            }
        }

        return view('commandes.create', compact(
            'panier', 'adresses', 'defaultLivraisonId', 'defaultFacturationId'
        ));
    }

    /**
     * Enregistre la commande puis redirige selon le mode de paiement.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        // on vérifie que les adresses appartiennent bien à l'utilisateur
        $data = $request->validate([
            'adresse_livraison_id' => [
                'required', 'integer',
                Rule::exists('adresses', 'id')->where('user_id', $userId),
            ],
            'adresse_facturation_id' => [
                'required', 'integer',
                Rule::exists('adresses', 'id')->where('user_id', $userId),
            ],
            'mode_paiement' => ['required', 'in:paypal,cheque'],
        ]);

        $commande = DB::transaction(function () use ($userId, $data) {
            // Panier courant avec puzzles pour récupérer les prix
            $panier = Panier::where('user_id', $userId)
                ->where('statut', 0)
                ->with('lignes.puzzle')
                ->lockForUpdate()           // évite les concurrences
                ->firstOrFail();

            if ($panier->lignes->isEmpty()) {
                abort(422, 'Panier vide.');
            }

            // === Calcul FIABLE du total ===
            $montantTotal = $panier->lignes->sum(function ($l) {
                $pu = $l->prix_unitaire ?? ($l->puzzle->prix ?? 0);
                return $pu * (int) $l->quantite;
            });

            // Création commande
            $commande = Commande::create([
                'user_id'                => $userId,
                'adresse_livraison_id'   => $data['adresse_livraison_id'],
                'adresse_facturation_id' => $data['adresse_facturation_id'],
                'mode_paiement'          => $data['mode_paiement'],
                'montant_total'          => $montantTotal,
                'date_commande'          => now(),
            ]);

            // Lignes de commande
            foreach ($panier->lignes as $ligne) {
                $pu = $ligne->prix_unitaire ?? ($ligne->puzzle->prix ?? 0);
                $totalLigne = $pu * (int) $ligne->quantite;

                Appartient::create([
                    'commande_id'   => $commande->id,
                    'puzzle_id'     => $ligne->puzzle_id,
                    'quantite'      => $ligne->quantite,
                    'prix_unitaire' => $pu,
                    'total_ligne'   => $totalLigne,
                ]);

                // Décrément stock
                if ($ligne->puzzle && $ligne->quantite > 0) {
                    $ligne->puzzle->decrement('stock', $ligne->quantite);
                }
            }

            // Clôture panier
            $panier->update(['statut' => 1]);

            return $commande;
        });

        if ($commande->mode_paiement === 'paypal') {
            return redirect()->route('paypal.start', $commande);
        }

        if ($commande->mode_paiement === 'cheque') {
            return redirect()
                ->route('commandes.merci', $commande)
                ->with('facture_ready', true);
        }

        return redirect()->route('commandes.index')->with('success', 'Commande enregistrée !');
    }

    /**
     * Affiche une commande précise.
     */
    public function show(Commande $commande)
    {
        abort_unless($commande->user_id === Auth::id(), 403);

        $commande->load([
            'adresseLivraison',
            'adresseFacturation',
            'lignes.puzzle.categorie',
        ]);

        return view('commandes.show', compact('commande'));
    }

    /**
     * Génère la facture PDF.
     */
    public function facture(Commande $commande)
    {
        $this->authorize('view', $commande);

        $commande->load(['lignes.puzzle','adresseLivraison','adresseFacturation','user']);

        return Pdf::loadView('commandes.facture', compact('commande'))
            ->setPaper('a4')
            ->stream();   // ou ->download('facture-'.$commande->id.'.pdf');
    }

    /**
     * Page "merci".
     */
    public function merci(Commande $commande)
    {
        abort_unless($commande->user_id === auth()->id(), 403);

        $commande->load(['lignes.puzzle', 'adresseLivraison', 'adresseFacturation']);

        return view('commandes.merci', compact('commande'));
    }
}
