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
        Schema::create('pos_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->boolean('qty_base_pricing')->default(0);
            $table->integer('qty')->nullable();
            $table->decimal('qty_amount', 10, 2)->nullable();
            $table->boolean('is_default')->default(0);
            $table->integer('sort_order')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_features');
    }
};
