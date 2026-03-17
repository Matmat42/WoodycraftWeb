<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('appartients', function (Blueprint $table) {
        $table->id(); // simple PK (plus simple que composite en Eloquent)
        $table->foreignId('panier_id')->constrained('paniers')->cascadeOnDelete(); // #FKPanier
        $table->foreignId('puzzle_id')->constrained('puzzles')->restrictOnDelete(); // #FKPuzzle
        $table->unsignedInteger('quantite');
        // prix figé au moment de l’ajout (utile si le prix change ensuite)
        $table->decimal('prix_unitaire', 10, 2);
        $table->decimal('total_ligne', 10, 2);
        $table->timestamps();

        $table->unique(['panier_id','puzzle_id']); // 1 ligne par puzzle
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartients');
    }
};
