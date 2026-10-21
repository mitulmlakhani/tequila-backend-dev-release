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
        Schema::create('chairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->references('id')->on('tables')->onDelete('cascade');
            $table->string('sorting_id')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('front_color')->nullable();
            $table->string('image')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('x_axis')->nullable();
            $table->string('y_axis')->nullable();
            $table->string('angle')->nullable();
            $table->string('booking_status')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chairs');
    }
};
