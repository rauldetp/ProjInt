<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn('taille');
            $table->unsignedInteger('nb_employes')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn('nb_employes');
            $table->enum('taille', ['petite', 'moyenne', 'grande'])->after('slug');
        });
    }
};
