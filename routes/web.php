<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\AdresseController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AvisController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('accueil');

Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategorieController::class, 'show'])
    ->whereNumber('id')
    ->name('categories.show');

Route::get('/puzzles/{puzzle}', [PuzzleController::class, 'show'])
    ->name('puzzles.show');

Route::get('commandes/{commande}/merci', [CommandeController::class, 'merci'])
    ->name('commandes.merci');

// Panier public (sans connexion obligatoire)
Route::prefix('dashboard')->group(function () {
    Route::get('panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('panier/add', [PanierController::class, 'add'])->name('panier.add');
    Route::patch('panier/ligne/{ligne}', [PanierController::class, 'update'])->name('panier.update');
    Route::delete('panier/ligne/{ligne}', [PanierController::class, 'remove'])->name('panier.remove');
    Route::post('panier/checkout', [PanierController::class, 'checkout'])->name('panier.checkout');
});

/*
|--------------------------------------------------------------------------
| Routes authentifiées
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/commandes/{commande}/avis/create', [AvisController::class, 'create'])
        ->name('avis.create');
    Route::post('/commandes/{commande}/avis', [AvisController::class, 'store'])
        ->name('avis.store');
});

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('puzzles', PuzzleController::class);
    Route::resource('adresses', AdresseController::class);
    Route::resource('categories', CategorieController::class)->except(['index', 'show']);

    Route::prefix('dashboard')->group(function () {
        Route::get('commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::get('commandes/create', [CommandeController::class, 'create'])
            ->middleware('has.address')
            ->name('commandes.create');
        Route::post('commandes', [CommandeController::class, 'store'])
            ->middleware('has.address')
            ->name('commandes.store');
        Route::get('dashboard/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
        Route::get('commandes/{commande}/facture', [CommandeController::class, 'facture'])
            ->name('commandes.facture');

        Route::get('paypal/{commande}/start', [PaypalController::class, 'start'])->name('paypal.start');
        Route::get('paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
        Route::get('paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');
    });
});

require __DIR__.'/auth.php';
