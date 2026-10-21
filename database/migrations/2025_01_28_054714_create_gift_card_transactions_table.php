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
        Schema::create('gift_card_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_card_id')->constrained('gift_cards')->onDelete('cascade'); // Association with Gift Cards
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null'); // Nullable payment method
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['add', 'deduct']);
            $table->string('transaction_id')->unique();
            $table->text('description')->nullable();
            $table->timestamp('transaction_date');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->unsignedBigInteger('created_by'); // Ensure consistency
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_card_transactions');
    }
};
