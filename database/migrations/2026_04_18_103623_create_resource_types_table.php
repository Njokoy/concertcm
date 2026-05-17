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
        Schema::create('resource_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name'); // ex: 'Stand A1', 'Billet VIP'
            $table->string('category'); // ex: 'ticket', 'stand', 'booth'
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('total_quantity')->unsigned();
            $table->integer('available_quantity')->unsigned();
            $table->json('attributes')->nullable(); // ex: dimensions, electricity for stands
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_types');
    }
};
