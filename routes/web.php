<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\AdresseController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaypalController;   // <— ajouté
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AvisController;
/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Accueil = liste des catégories
Route::get('/', [HomeController::class, 'index'])->name('accueil');

Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');

// Détail d’une catégorie = liste des puzzles
Route::get('/categories/{id}', [CategorieController::class, 'show'])
    ->whereNumber('id')
    ->name('categories.show');

    Route::get('/puzzles/{puzzle}', [PuzzleController::class, 'show'])
    ->name('puzzles.show');

    // Merci / confirmation (après paiement chèque)
Route::get('commandes/{commande}/merci', [CommandeController::class, 'merci'])
->name('commandes.merci');

// routes/web.php
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/commandes/{commande}/avis/create', [\App\Http\Controllers\AvisController::class, 'create'])
        ->name('avis.create');
    Route::post('/commandes/{commande}/avis', [\App\Http\Controllers\AvisController::class, 'store'])
        ->name('avis.store');
});

/*
|--------------------------------------------------------------------------
| Dashboard / Espace authentifié
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD puzzles
    Route::resource('puzzles', PuzzleController::class);

    // CRUD adresses
    Route::resource('adresses', AdresseController::class);

    // CRUD catégories (admin) — on exclut index & show car publics ci-dessus
    Route::resource('categories', CategorieController::class);

    // Espace dashboard (panier + commandes + paiement)
    Route::prefix('dashboard')->group(function () {
        // Panier
        Route::get('panier', [PanierController::class, 'index'])->name('panier.index');
        Route::post('panier/add', [PanierController::class, 'add'])->name('panier.add');
        Route::patch('panier/ligne/{ligne}', [PanierController::class, 'update'])->name('panier.update');
        Route::delete('panier/ligne/{ligne}', [PanierController::class, 'remove'])->name('panier.remove');
        Route::post('panier/checkout', [PanierController::class, 'checkout'])->name('panier.checkout');

        // Commandes
        Route::get('commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::get('commandes/create', [CommandeController::class, 'create'])
            ->middleware('has.address')
            ->name('commandes.create');
        Route::post('commandes', [CommandeController::class, 'store'])
        ->middleware('has.address')
            ->name('commandes.store');
        Route::get('dashboard/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');

        // Facture PDF (paiement par chèque)
        Route::get('commandes/{commande}/facture', [CommandeController::class, 'facture'])
            ->name('commandes.facture');

        // PayPal (stubs)
        Route::get('paypal/{commande}/start', [PaypalController::class, 'start'])->name('paypal.start');
        Route::get('paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
        Route::get('paypal/cancel',  [PaypalController::class, 'cancel'])->name('paypal.cancel');
    });
});

require __DIR__.'/auth.php';
