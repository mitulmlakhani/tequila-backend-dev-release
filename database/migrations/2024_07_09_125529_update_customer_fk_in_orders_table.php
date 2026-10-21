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
            // Make sure to adjust the constraint name if it differs
            $table->dropForeign(['customer_id']);  // Drop the old foreign key
            $table->foreign('customer_id')
                  ->references('id')->on('reservation_customers')  // Update to point to reservation_customers
                  ->onDelete('cascade');  // Ensure cascade on delete if that's desired behavior
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);  // Drop the new foreign key
            $table->foreign('customer_id')
                  ->references('id')->on('customers')  // Revert back to the original customers table
                  ->onDelete('cascade');  // Ensure cascade on delete if that was the original behavior
        });
    }
};
