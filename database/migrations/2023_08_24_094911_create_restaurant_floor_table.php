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
        Schema::create('restaurant_floor', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->nullable();
            $table->bigInteger('floor_id')->nullable();
            $table->enum('status',['Active','In-active'])->default('In-active');
            $table->timestamps();
            $table->Integer('created_by')->nullable();
            $table->Integer('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_floor');
    }
};
