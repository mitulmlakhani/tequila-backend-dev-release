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
        // Add discount column to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->default(0)->after('total_amount')->comment('Discount applied to the entire order');
        });

        // Add discount column to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->default(0)->after('total_amount')->comment('Discount applied to individual order items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
};
