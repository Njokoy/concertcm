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
        Schema::table('events', function (Blueprint $table) {
            $table->string('label')->nullable(); // Vedette, Certifié, etc.
            $table->boolean('is_verified')->default(false);
        });

        Schema::table('concerts', function (Blueprint $table) {
            $table->string('label')->nullable();
            $table->boolean('is_verified')->default(false);
        });

        Schema::table('artists', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false);
            $table->string('verification_badge')->nullable(); // silver, gold, etc.
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['label', 'is_verified']);
        });

        Schema::table('concerts', function (Blueprint $table) {
            $table->dropColumn(['label', 'is_verified']);
        });

        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'verification_badge']);
        });
    }
};
