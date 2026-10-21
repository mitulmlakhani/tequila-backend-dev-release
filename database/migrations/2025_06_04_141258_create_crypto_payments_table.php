<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
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
        Schema::create('crypto_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('crypto', ['bitcoin']);
            $table->foreignIdFor(Restaurant::class)->nullable()->constrained();
            $table->foreignIdFor(Order::class)->nullable()->constrained();
            $table->foreignIdFor(Payment::class)->nullable()->constrained();
            $table->string('address');
            $table->string('tx_hash');
            $table->string("tx_amount");
            $table->string("payment_amount");
            $table->string("fees");
            $table->string("rate");

            $table->string('restaurant_address')->nullable();
            $table->string('restaurant_tx_hash')->nullable();
            $table->string('restaurant_amount')->nullable();
            $table->text('restaurant_tx_response')->nullable();
            $table->string('admin_address')->nullable();
            $table->string('admin_amount')->nullable();
            $table->string('admin_tx_hash')->nullable();
            $table->text('admin_tx_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_payments');
    }
};
