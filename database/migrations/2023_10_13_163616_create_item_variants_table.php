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
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('name');
            $table->string('price');
            $table->string('quantity')->default(0);
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
