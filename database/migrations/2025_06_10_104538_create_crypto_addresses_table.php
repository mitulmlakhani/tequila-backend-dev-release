<?php

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
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
        Schema::create('crypto_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class)->constrained();
            $table->foreignIdFor(Order::class)->constrained();
            $table->enum('crypto', ['bitcoin']);
            $table->string('address');
            $table->text('pk');
            $table->text('mnemonic')->nullable();
            $table->string("fiat_amount")->default(0);
            $table->string("amount")->default(0);
            $table->string("fees_amount")->default(0);
            $table->string("payment_amount")->default(0);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_processed')->default(false);
            $table->string('device_id')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->json('rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_addresses');
    }
};
