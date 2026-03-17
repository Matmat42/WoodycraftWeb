<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puzzles', function (Blueprint $table) {
            if (!Schema::hasColumn('puzzles', 'categorie_id')) {
                $table->unsignedBigInteger('categorie_id')->nullable()->after('id');
                $table->foreign('categorie_id')
                      ->references('id')
                      ->on('categories')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('puzzles', function (Blueprint $table) {
            if (Schema::hasColumn('puzzles', 'categorie_id')) {
                $table->dropForeign(['categorie_id']);
                $table->dropColumn('categorie_id');
            }
        });
    }
};
