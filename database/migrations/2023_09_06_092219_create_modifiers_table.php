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
        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->references('id')->on('modifier_categories')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('modifiers');
    }
};
