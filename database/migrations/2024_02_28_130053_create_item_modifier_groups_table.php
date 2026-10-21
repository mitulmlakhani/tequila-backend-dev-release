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
        Schema::create('item_modifier_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->nullable()->references('id')->on('items')->onDelete('cascade');
            $table->foreignId('modifier_group_id')->nullable()->references('id')->on('modifier_groups')->onDelete('cascade');
            $table->string('free_modifier_count')->nullable();
            $table->string('is_multi_select')->nullable();
            $table->string('is_forced_modifier_group')->nullable();
            $table->string('min_modifier_count')->nullable();
            $table->string('max_modifier_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_modifier_groups');
    }
};
