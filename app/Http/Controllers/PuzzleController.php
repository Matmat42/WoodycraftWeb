<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Categorie;

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
        $categories = Categorie::all();
        return view('puzzles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'nom'          => 'required|string|max:100|unique:puzzles,nom',
        'categorie_id' => 'required|exists:categories,id',
        'description'  => 'nullable|string|max:500',
        'image'        => 'nullable|string|max:255',
        'prix'         => 'required|numeric|min:0|max:999999.99',
        'stock'        => 'required|integer|min:0',   // <--
    ]);

    Puzzle::create($data);  // <--- enregistre aussi "stock"

    return redirect()->route('puzzles.index')
        ->with('message', 'Le puzzle a bien été créé !');
}

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Puzzle $puzzle)
    {
        $puzzle->load('categorie');   // <-- charge la relation
        return view('puzzles.show', compact('puzzle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $puzzle = Puzzle::findOrFail($id);
        return view('puzzles.edit', compact('puzzle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $data = $request->validate([
        'nom'         => 'required|max:100',
        'categorie'   => 'required|max:100',
        'description' => 'required|max:500',
        'prix'        => 'required|numeric|between:0,99.99',
    ]);

    $puzzle = Puzzle::findOrFail($id);

    $puzzle->nom         = $data['nom'];
    $puzzle->categorie   = $data['categorie'];
    $puzzle->description = $data['description'];
    $puzzle->prix        = $data['prix'];
    $puzzle->save();

    return redirect()
        ->route('puzzles.index') // ou show
        ->with('message', 'Le puzzle a bien été modifié.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $puzzle = Puzzle::findOrFail($id);
        $puzzle->delete();
        return redirect()
            ->route('puzzles.index')
            ->with('message', 'Le puzzle a bien été supprimé.');
    }
}
