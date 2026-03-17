<?php

// app/Http/Controllers/AvisController.php
namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    public function create(Commande $commande)
    {
        abort_unless($commande->user_id === Auth::id(), 403);

        if ($commande->avis()->where('user_id', Auth::id())->exists()) {
            return redirect()
                ->route('commandes.show', $commande)
                ->with('message', 'Vous avez déjà laissé un avis pour cette commande.');
        }

        return view('avis.create', compact('commande'));
    }

    public function store(Request $request, Commande $commande)
    {
        abort_unless($commande->user_id === Auth::id(), 403);

        $data = $request->validate([
            'note'        => ['required','integer','min:1','max:5'],
            'commentaire' => ['nullable','string','max:2000'],
        ]);

        Avis::create([
            'commande_id' => $commande->id,
            'user_id'     => Auth::id(),
            'note'        => $data['note'],
            'commentaire' => $data['commentaire'] ?? null,
        ]);

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', 'Merci pour votre avis !');
    }
}
