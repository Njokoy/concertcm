<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer la clé étrangère existante s'il y en a pour éviter les erreurs
        // Et on ajoute les colonnes polymorphiques
        Schema::table('resource_types', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
            
            // Ajout des nouvelles colonnes polymorphiques
            $table->morphs('resourceable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_types', function (Blueprint $table) {
            $table->dropColumn(['resourceable_id', 'resourceable_type']);
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        });
    }
};
