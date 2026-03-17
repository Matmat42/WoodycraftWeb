<?php

namespace App\Http\Controllers;

use App\Models\{Panier, Appartient, Puzzle};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    protected function currentCart(): Panier
    {
        return Panier::firstOrCreate([
            'user_id' => Auth::id(),
            'statut'  => 0, // brouillon
        ]);
    }

    public function index()
    {
        $panier = $this->currentCart()->load('lignes.puzzle');
        return view('panier.index', compact('panier'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'puzzle_id' => ['required', 'exists:puzzles,id'],
            'quantite'  => ['nullable', 'integer', 'min:1'],
        ]);

        $quantite = max(1, (int)($data['quantite'] ?? 1));
        $panier   = $this->currentCart();
        $puzzle   = Puzzle::findOrFail($data['puzzle_id']);

        $ligne = Appartient::firstOrNew([
            'panier_id' => $panier->id,
            'puzzle_id' => $puzzle->id,
        ]);

        $ligne->quantite      = max(1, (int)($ligne->quantite ?? 0) + $quantite);
        $ligne->prix_unitaire = $ligne->prix_unitaire ?? $puzzle->prix;
        $ligne->save();

        return redirect()->route('panier.index')->with('message', 'Ajouté au panier');
    }

    public function update(Request $request, Appartient $ligne)
    {
        abort_unless($ligne->panier && $ligne->panier->user_id === Auth::id(), 403);

        $data = $request->validate([
            'quantite' => ['required','integer','min:1'],
        ]);

        // (optionnel) vérifier stock si tu as le champ
        if (isset($ligne->puzzle->stock) && $data['quantite'] > $ligne->puzzle->stock) {
            return back()->withErrors(['quantite' => 'Stock insuffisant']);
        }

        $ligne->update(['quantite' => $data['quantite']]);

        return back()->with('message', 'Quantité mise à jour');
    }

    public function remove(Appartient $ligne)
    {
        abort_unless($ligne->panier && $ligne->panier->user_id === Auth::id(), 403);
        $ligne->delete();
        return back()->with('message', 'Article retiré du panier');
    }
}
