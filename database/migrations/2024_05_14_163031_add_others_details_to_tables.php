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
        Schema::table('tables', function (Blueprint $table) {
            $table->after('seating_capacity', function ($table) {
                $table->string('sorting_id')->nullable();
                $table->string('bg_color')->nullable();
                $table->string('front_color')->nullable();
                $table->string('image')->nullable();
                $table->string('length')->nullable();
                $table->string('width')->nullable();
                $table->string('radius')->nullable();
                $table->string('x_axis')->nullable();
                $table->string('y_axis')->nullable();
                $table->string('angle')->nullable();
                $table->string('shape')->nullable();
                $table->string('type')->nullable()->comment('1->"Table",2->"Wall",3->"Garden",4->"Sofa",5->"Partition",6->"Custom"')->default("1");
                $table->string('booking_status')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
