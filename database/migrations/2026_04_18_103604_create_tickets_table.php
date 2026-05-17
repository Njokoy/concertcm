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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('concert_id')->constrained('concerts');
            $table->foreignId('user_id')->constrained('users');
            $table->string('reference')->unique();
            $table->decimal('price_paid', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'used', 'cancelled', 'refunded'])->default('pending');
            $table->string('qr_code_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->foreignId('scanned_by')->nullable()->constrained('users');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
