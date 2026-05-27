<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collectes', function (Blueprint $table) {
            $table->string('lieu')->nullable()->after('sur_site');
            $table->string('horaires')->nullable()->after('lieu');
            $table->integer('objectif_dons')->nullable()->after('horaires');
        });
    }

    public function down(): void
    {
        Schema::table('collectes', function (Blueprint $table) {
            $table->dropColumn(['lieu', 'horaires', 'objectif_dons']);
        });
    }
};
