<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // si la colonne user_id existe déjà, laisse-la
            if (!Schema::hasColumn('commandes', 'adresse_livraison_id')) {
                $table->unsignedBigInteger('adresse_livraison_id')->after('user_id');
                $table->foreign('adresse_livraison_id')
                      ->references('id')->on('adresses')
                      ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('commandes', 'adresse_facturation_id')) {
                $table->unsignedBigInteger('adresse_facturation_id')->after('adresse_livraison_id');
                $table->foreign('adresse_facturation_id')
                      ->references('id')->on('adresses')
                      ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('commandes', 'mode_paiement')) {
                $table->enum('mode_paiement', ['paypal', 'cheque'])->default('paypal');
            }

            if (!Schema::hasColumn('commandes', 'total')) {
                $table->decimal('total', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'adresse_livraison_id')) {
                $table->dropForeign(['adresse_livraison_id']);
                $table->dropColumn('adresse_livraison_id');
            }
            if (Schema::hasColumn('commandes', 'adresse_facturation_id')) {
                $table->dropForeign(['adresse_facturation_id']);
                $table->dropColumn('adresse_facturation_id');
            }
            if (Schema::hasColumn('commandes', 'mode_paiement')) {
                $table->dropColumn('mode_paiement');
            }
            if (Schema::hasColumn('commandes', 'total')) {
                $table->dropColumn('total');
            }
        });
    }
};
