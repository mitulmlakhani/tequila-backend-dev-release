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
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_number')->unique();
            $table->decimal('initial_amount', 10, 2);
            $table->decimal('current_balance', 10, 2);
            $table->date('expiration_date');
            $table->enum('status', ['active', 'inactive', 'expired', 'redeemed'])->default('active');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
               
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
