<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;

class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $puzzles = Puzzle::all();
        return view('puzzles.index', compact('puzzles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('puzzles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = $request->validate([
            'nom' => 'required|max:100',
            'categorie' => 'required|max:100',
            'description' => 'required|max:500',
            'image' => 'required|max:100',
            'prix' => 'required|numeric|between:0,99.99',
        ]);

        $puzzle = new Puzzle();
        $puzzle->nom = $request->nom;
        $puzzle->categorie = $request->categorie;
        $puzzle->description = $request->description;
        $puzzle->image = $request->image;
        $puzzle->prix = $request->prix;
        $puzzle->save();
        return back()->with('message',"Le puzzle a bien été crée !");
    }


    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {
        return view('puzzles.show', compact('puzzle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puzzle $puzzle)
    {
        return view('puzzles.edit', compact('puzzle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puzzle $puzzle)
    {
        $data = $request->validate([
            'nom' => 'required|max:100',
            'categorie' => 'required|max:100',
            'description' => 'required|max:500',
            'image' => 'required|max:100',
            'prix' => 'required|numeric|between:0,99.99',
        ]);

        $puzzle->nom = $data['nom'];
        $puzzle->categorie = $data['categorie'];
        $puzzle->description = $data['description'];
        $puzzle->image = $data['image'];
        $puzzle->prix = $data['prix'];

        $puzzle->save();
        return back()->with('message',"Le puzzle a bien été modifié !");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puzzle $puzzle)
    {
        $puzzle->delete();
        return redirect()->route('puzzles.index')  // redirige vers la liste des puzzles
        ->with('success', 'Puzzle supprimé avec succès.');
    }


    public function byCategorie($id)
    {
        // Récupérer la catégorie avec ses puzzles
        $categorie = Categorie::with('puzzles')->find($id);

        // Vérifier si la catégorie existe
        if (!$categorie) {
            return redirect()->route('home')->with('error', 'Catégorie non trouvée');
        }

        // Passer la catégorie et ses puzzles à la vue
        return view('categorie.byCategorie', compact('categorie'));
    }
}
