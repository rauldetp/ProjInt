<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn('domaine');
            $table->enum('domaine', ['horlogerie', 'banque', 'assurance', 'sante', 'industrie', 'technologie', 'autre'])->nullable()->after('nb_employes');
            $table->unsignedInteger('npa')->nullable()->change();
            $table->foreignId('parent_id')->nullable()->constrained('entreprises')->nullOnDelete()->after('couleur_primaire');
        });
    }

    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropColumn('domaine');
            $table->string('domaine')->nullable()->after('nb_employes');
            $table->string('npa', 10)->nullable()->change();
        });
    }
};
