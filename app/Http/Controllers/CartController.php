<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;

class CartController extends Controller
{
    // Ajouter un puzzle au panier
    public function addPuzzleToCart(Request $request, $id)
    {
        // Récupérer le puzzle par son ID
        $puzzle = Puzzle::findOrFail($id);

        // Récupérer le panier actuel depuis la session
        $cart = session()->get('cart', []);

        // Vérifier si le puzzle est déjà dans le panier
        if (isset($cart[$id])) {
            // Si le puzzle est déjà dans le panier, on incrémente la quantité
            $cart[$id]['quantity']++;
        } else {
            // Sinon, on ajoute le puzzle au panier avec une quantité de 1
            $cart[$id] = [
                'nom' => $puzzle->nom,
                'prix' => $puzzle->prix,
                'image' => $puzzle->image,
                'quantity' => 1,
            ];
        }

        // Mettre à jour le panier dans la session
        session()->put('cart', $cart);

        // Retourner à la page précédente avec un message de succès
        return redirect()->back()->with('success', 'Puzzle ajouté au panier');
    }
}
