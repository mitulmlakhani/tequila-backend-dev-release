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
        Schema::table('order_tables', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['order_id']);

            // Add the new foreign key constraint, referencing parent_orders table
            $table->foreign('order_id')->references('id')->on('parent_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_tables', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['order_id']);

            // Restore the original foreign key constraint, referencing orders table
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};
