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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('menucategorys')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('time')->nullable();
            $table->foreignId('uom')->nullable()->references('id')->on('unit_of_measurements')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_variant')->default(false);
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
