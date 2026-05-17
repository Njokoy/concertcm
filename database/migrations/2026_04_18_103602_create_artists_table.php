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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('genre')->nullable();
            $table->json('genres')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Cameroun');
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('spotify')->nullable();
            $table->string('soundcloud')->nullable();
            $table->string('website')->nullable();
            $table->integer('followers_count')->unsigned()->default(0);
            $table->integer('concerts_count')->unsigned()->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
