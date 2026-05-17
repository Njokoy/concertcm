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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('event_type'); // ex: 'fair', 'cultural', 'festival'
            $table->text('description')->nullable();
            $table->string('banner')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('venue_id')->constrained('venues');
            $table->foreignId('organizer_id')->constrained('users');
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->json('meta_data')->nullable(); // For generic extension
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
