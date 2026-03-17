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
    Schema::table('adresses', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->enum('type', ['livraison','facturation'])->default('livraison');
    });
}
    public function down(): void
    {
        Schema::table('adresses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('type');
        });
}
};
