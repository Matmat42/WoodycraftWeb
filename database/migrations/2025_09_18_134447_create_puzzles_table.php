<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('puzzles', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->foreignId('categorie_id')->constrained('categories')->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->decimal('prix', 10, 2);
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puzzles');
    }
};
