<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collectes', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['validated_by', 'validated_at']);
            $table->enum('statut', ['en_attente', 'validee', 'refusee'])->default('en_attente')->after('active');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('entreprise_id');
            $table->foreignId('coordinateur_id')->nullable()->constrained('coordinateurs')->nullOnDelete()->after('admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('collectes', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['coordinateur_id']);
            $table->dropColumn(['admin_id', 'coordinateur_id', 'statut']);
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
        });
    }
};
