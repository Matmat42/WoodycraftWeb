<?php

namespace App\Http\Controllers;

use App\Models\Adresse; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdresseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adresses = \App\Models\Adresse::where('user_id', Auth::id())
            ->latest()->get();

        return view('adresses.index', compact('adresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $next = $request->query('next', route('commandes.create'));
        return view('adresses.create', compact('next'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero'      => ['required','string','max:50'],
            'rue'         => ['required','string','max:255'],
            'ville'       => ['required','string','max:255'],
            'code_postal' => ['required','string','max:20'],
            'pays'        => ['required','string','max:100'],
        ]);

        $data['user_id'] = Auth::id();

        $adresse = \App\Models\Adresse::create($data);

        // S'il y a un "next" dans l'URL ou le formulaire, on y retourne
        $next = $request->input('next');
        if ($next && url()->previous()) {
            return redirect($next)->with('success', 'Adresse enregistrée.');
        }

        // Sinon on va valider la commande
        return redirect()->route('commandes.create')->with('success', 'Adresse enregistrée.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adresse $adresse)
    {
        return view('adresses.edit', compact('adresse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adresse $adresse)
    {
        $request->validate([
            'numero' => 'nullable|string|max:10',
            'rue' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:20',
            'pays' => 'required|string|max:100',
        ]);

        $adresse->update($request->all());

        return redirect()->route('adresses.index')
                         ->with('success', 'Adresse modifiée avec succès.');
    }

    // Suppression
    public function destroy(Adresse $adresse)
    {
        $adresse->delete();

        return redirect()->route('adresses.index')
                         ->with('success', 'Adresse supprimée avec succès.');
    }
}

