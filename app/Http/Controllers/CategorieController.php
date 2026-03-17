<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Puzzle;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('categories.index', compact('categories'));
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
    public function show(int $id)
     {
         $categorie = Categorie::findOrFail($id);
 
         // ✅ Bien filtrer sur 'categorie_id'
         $puzzles = Puzzle::where('categorie_id', $id)
             ->latest('id')
             ->get();
 
         return view('categories.show', compact('categorie', 'puzzles'));
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
