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
        Schema::dropIfExists('order_item_modifiers');
    
        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->nullable()->references('id')->on('order_items')->onDelete('cascade');
            $table->foreignId('item_modifier_group_detail_id')->nullable()->references('id')->on('item_modifier_group_details')->onDelete('cascade');
            $table->string('modifier_group_name')->nullable();
            $table->string('modifier_name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
