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
    Schema::create('entreprises', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('slug')->unique();
        $table->enum('taille', ['petite', 'moyenne', 'grande']);
        $table->string('domaine')->nullable();
        $table->string('adresse')->nullable();
        $table->string('ville')->nullable();
        $table->string('npa', 10)->nullable();
        $table->string('logo')->nullable();
        $table->string('couleur_primaire', 7)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
