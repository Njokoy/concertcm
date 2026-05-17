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
        // Add missing moderation columns to concerts (safe - skip if exists)
        Schema::table('concerts', function (Blueprint $table) {
            if (!Schema::hasColumn('concerts', 'label')) {
                $table->string('label')->nullable();
            }
            if (!Schema::hasColumn('concerts', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'label')) {
                $table->string('label')->nullable();
            }
            if (!Schema::hasColumn('events', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
        });

        Schema::table('artists', function (Blueprint $table) {
            if (!Schema::hasColumn('artists', 'verification_badge')) {
                $table->string('verification_badge')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            $table->dropColumnIfExists(['label', 'is_verified']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumnIfExists(['label', 'is_verified']);
        });
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumnIfExists(['verification_badge']);
        });
    }
};
