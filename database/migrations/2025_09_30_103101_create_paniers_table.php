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
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK utilisateur
            $table->unsignedTinyInteger('statut')->default(0); // 0=ouvert,1=validé,2=payé,3=expédié
            $table->string('mode_paiement')->nullable();
            $table->foreignId('adresse_livraison_id')->nullable()->constrained('adresses')->nullOnDelete();
            $table->foreignId('adresse_facturation_id')->nullable()->constrained('adresses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paniers');
    }
};
