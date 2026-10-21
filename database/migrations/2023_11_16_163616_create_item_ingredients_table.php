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
        Schema::create('item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreignId('item_variant_id')->nullable()->references('id')->on('item_variants')->onDelete('cascade');
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->foreignId('uom_id')->references('id')->on('unit_of_measurements')->onDelete('cascade');
            $table->string('quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_ingredients');
    }
};
