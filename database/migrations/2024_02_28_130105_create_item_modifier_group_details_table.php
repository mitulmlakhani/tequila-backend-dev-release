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
        Schema::create('item_modifier_group_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_modifier_group_id')->references('id')->on('item_modifier_groups')->onDelete('cascade');
            $table->foreignId('modifier_id')->nullable()->references('id')->on('modifiers')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_modifier_group_details');
    }
};
