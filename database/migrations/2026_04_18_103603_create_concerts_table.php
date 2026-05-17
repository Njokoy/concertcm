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
        Schema::create('concerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('poster')->nullable();
            $table->json('gallery')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('timezone')->default('Africa/Douala');
            $table->foreignId('venue_id')->constrained('venues');
            $table->foreignId('organizer_id')->constrained('users');
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->integer('capacity')->unsigned()->nullable();
            $table->tinyInteger('min_age')->unsigned()->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('featured')->default(false);
            $table->timestamp('featured_until')->nullable();
            $table->integer('views_count')->unsigned()->default(0);
            $table->integer('tickets_sold')->unsigned()->default(0);
            $table->decimal('revenue_total', 12, 2)->default(0.00);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concerts');
    }
};
