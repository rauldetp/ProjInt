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
    Schema::create('trophees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('entreprise_id')->constrained('entreprises')->cascadeOnDelete();
        $table->integer('annee')->unique();
        $table->string('commentaire')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trophees');
    }
};
