<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Puzzle;

class HomeController extends Controller
{
    public function index()
    {
        // Catégories mises en avant (exemple simple)
        $categories = Categorie::select('id','nom')
            ->latest('id')
            ->take(4)
            ->get();

        // Produits récents (exemple simple)
        $produits = Puzzle::select('id','nom','prix','categorie_id')
            ->latest('id')
            ->take(6)
            ->get();

        return view('home.index', compact('categories','produits'));
    }
}
