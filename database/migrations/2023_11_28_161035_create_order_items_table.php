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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('item_variant_id')->nullable()->references('id')->on('item_variants')->onDelete('cascade');
            $table->string('item_name')->nullable();
            $table->string('item_variant_name')->nullable();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_modifiers')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
