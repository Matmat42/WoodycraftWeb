<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use App\Models\Panier;
use App\Models\Appartient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    /**
     * Ajouter un produit au panier.
     */
    public function ajouter(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour ajouter des produits au panier.');
        }

        // Récupérer le produit et la quantité
        $produitId = $request->input('produit_id');
        $quantite = $request->input('quantite', 1); // Quantité par défaut à 1 si non spécifiée

        // Trouver le produit
        $produit = Puzzle::find($produitId);
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé.');
        }

        // Vérifier si l'utilisateur a déjà un panier actif
        $panier = Panier::where('id_Utilisateur', Auth::id())
                        ->where('statuts', 0) // 0 signifie que le panier est en cours
                        ->first();

        // Si l'utilisateur n'a pas de panier actif, en créer un
        if (!$panier) {
            $panier = Panier::create([
                'statuts' => 0, // 0 = panier en cours
                'total' => 0,   // Total du panier, à calculer plus tard
                'mode_paiement' => false, // On peut ajouter un mode de paiement plus tard
                'id_Utilisateur' => Auth::id(),
            ]);
        }

        // Vérifier si le produit existe déjà dans le panier
        $appartient = Appartient::where('id_Panier', $panier->id)
                               ->where('id_Puzzle', $produitId)
                               ->first();

        if ($appartient) {
            // Si le produit est déjà dans le panier, on met à jour la quantité
            $appartient->quantite += $quantite;
            $appartient->save();
        } else {
            // Sinon, on ajoute le produit au panier
            Appartient::create([
                'id_Panier' => $panier->id,
                'id_Puzzle' => $produitId,
                'quantite' => $quantite,
            ]);
        }

        // Recalculer le total du panier
        $this->recalculerTotal($panier);

        return redirect()->route('panier.afficher')->with('success', 'Produit ajouté au panier.');
    }

    /**
     * Afficher le contenu du panier.
     */
    public function afficher()
    {
        // Récupérer le panier de l'utilisateur authentifié
        $panier = Panier::where('id_Utilisateur', Auth::id())
                        ->where('statuts', 0) // Seulement les paniers en cours
                        ->first();

        // Récupérer tous les produits dans le panier
        $produits = [];
        if ($panier) {
            $produits = Appartient::where('id_Panier', $panier->id)
                                  ->join('puzzles', 'appartient.id_Puzzle', '=', 'puzzles.id')
                                  ->get();
        }

        return view('panier', compact('panier', 'produits'));
    }

    /**
     * Supprimer un produit du panier.
     */
    public function supprimer($idPuzzle)
    {
        // Trouver le panier de l'utilisateur
        $panier = Panier::where('id_Utilisateur', Auth::id())
                        ->where('statuts', 0) // Seulement les paniers en cours
                        ->first();

        // Supprimer le produit du panier
        if ($panier) {
            $appartient = Appartient::where('id_Panier', $panier->id)
                                   ->where('id_Puzzle', $idPuzzle)
                                   ->first();

            if ($appartient) {
                $appartient->delete();
                $this->recalculerTotal($panier);
            }
        }

        return redirect()->route('panier.afficher')->with('success', 'Produit supprimé du panier.');
    }

    /**
     * Recalculer le total du panier.
     */
    private function recalculerTotal($panier)
    {
        $total = 0;

        // Calculer le total du panier
        $appartientItems = Appartient::where('id_Panier', $panier->id)
                                     ->join('puzzles', 'appartient.id_Puzzle', '=', 'puzzles.id')
                                     ->get();

        foreach ($appartientItems as $item) {
            $total += $item->quantite * $item->prix;
        }

        // Mettre à jour le total du panier
        $panier->total = $total;
        $panier->save();
    }
}
