<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Afficher la liste des catégories sur la page d'accueil (dashboard).
     */
    public function index()
    {
        // Récupérer toutes les catégories depuis la table categories
        $categories = Categorie::all();  // Tu peux aussi filtrer les catégories si nécessaire

        // Passer les catégories à la vue dashboard.blade.php
        return view('dashboard', compact('categories'));  // On passe $categories à la vue dashboard
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupérer la catégorie avec ses produits
        $categorie = Categorie::with('puzzles')->find($id);

        // Vérifier si la catégorie existe
        if (!$categorie) {
            return redirect()->route('home')->with('error', 'Catégorie non trouvée');
        }

        // Passer les données à la vue
        return view('categorie.show', compact('categorie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
