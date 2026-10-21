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
        Schema::create('restaurant_delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Restaurant::class)->constrained()->onDelete('cascade');
            $table->enum('delivery_partner', ['ubereats', 'doordash', 'grubhub']);
            $table->string('merchant_id')->unique();
            $table->text('token')->nullable();
            $table->string('token_expires_at')->nullable();
            $table->json('config')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_delivery_partners');
    }
};
