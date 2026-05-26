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
    Schema::create('collectes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('entreprise_id')->constrained('entreprises')->cascadeOnDelete();
        $table->date('date_debut');
        $table->date('date_fin');
        $table->boolean('sur_site')->default(true);
        $table->string('lien_rdv_externe')->nullable();
        $table->boolean('active')->default(true);
        $table->integer('nb_inscrits_estime')->default(0);
        $table->integer('nb_dons_realises')->default(0);
        $table->integer('nb_nouveaux_donneurs')->default(0);
        $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('validated_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};
