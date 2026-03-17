<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    public function start(Commande $commande)
    {
        abort_unless($commande->user_id === Auth::id(), 403);

        // Ici, dans un vrai flux, tu créerais un "order" PayPal et redirigerais vers l'approbation.
        // Pour la tâche demandée : simple redirection vers la page PayPal FR.
        return redirect()->away('https://www.paypal.com/fr/home');
    }

    public function success(Request $request)
    {
        // Dans un vrai flux : vérifier/capturer l’ordre PayPal puis marquer la commande "payée".
        return redirect()->route('commandes.show', ['commande' => $request->get('id')])
            ->with('success', 'Paiement PayPal validé (stub).');
    }

    public function cancel()
    {
        return redirect()->route('commandes.index')
            ->with('error', 'Paiement PayPal annulé.');
    }
}
