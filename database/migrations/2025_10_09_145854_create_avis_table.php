<?php

// database/migrations/2025_10_09_000000_create_avis_table.php
// database/migrations/2025_10_09_000000_create_avis_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('note');              // 1..5
            $table->text('commentaire')->nullable();
            $table->timestamps();

            // 1 avis par utilisateur et par commande
            $table->unique(['commande_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
