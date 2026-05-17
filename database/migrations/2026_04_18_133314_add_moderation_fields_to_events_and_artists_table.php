<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'label')) {
                $table->string('label')->nullable(); // Vedette, Certifié, etc.
            }
            if (!Schema::hasColumn('events', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
        });

        Schema::table('concerts', function (Blueprint $table) {
            if (!Schema::hasColumn('concerts', 'label')) {
                $table->string('label')->nullable();
            }
            if (!Schema::hasColumn('concerts', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
        });

        Schema::table('artists', function (Blueprint $table) {
            if (!Schema::hasColumn('artists', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
            if (!Schema::hasColumn('artists', 'verification_badge')) {
                $table->string('verification_badge')->nullable(); // silver, gold, etc.
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'label')) {
                $table->dropColumn('label');
            }
            if (Schema::hasColumn('events', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });

        Schema::table('concerts', function (Blueprint $table) {
            if (Schema::hasColumn('concerts', 'label')) {
                $table->dropColumn('label');
            }
            if (Schema::hasColumn('concerts', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });

        Schema::table('artists', function (Blueprint $table) {
            if (Schema::hasColumn('artists', 'verification_badge')) {
                $table->dropColumn('verification_badge');
            }
        });
    }
};
