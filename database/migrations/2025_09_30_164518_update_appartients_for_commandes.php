<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appartients', function (Blueprint $table) {
            // panier_id devient nullable (si elle ne l’est pas)
            $table->unsignedBigInteger('panier_id')->nullable()->change();

            // ajoute commande_id nullable + FK
            if (!Schema::hasColumn('appartients', 'commande_id')) {
                $table->unsignedBigInteger('commande_id')->nullable()->after('panier_id');
                $table->foreign('commande_id')->references('id')->on('commandes')->nullOnDelete();
            }

            // ajoute prix si manquant
            if (!Schema::hasColumn('appartients', 'prix')) {
                $table->decimal('prix', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('appartients', function (Blueprint $table) {
            if (Schema::hasColumn('appartients', 'commande_id')) {
                $table->dropForeign(['commande_id']);
                $table->dropColumn('commande_id');
            }
            // on ne remet pas panier_id en NOT NULL pour éviter des soucis de rollback
            if (Schema::hasColumn('appartients', 'prix')) {
                $table->dropColumn('prix');
            }
        });
    }
};
