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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('discount_notes');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->mediumText('discount_notes')->after('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('discount_notes');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->mediumText('discount_notes')->after('discount_type')->nullable();
        });
    }
};
