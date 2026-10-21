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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('chair_color')->nullable()->default('#ffffff'); // Add chair color
            $table->string('seat_number')->nullable()->default(0); // Add chair color
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->string('split_type')->nullable(); // Add split type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('chair_color');
            $table->dropColumn('seat_number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('split_type');
        });
    }
};
