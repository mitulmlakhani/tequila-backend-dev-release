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
        // First drop the existing foreign key on payment_method_id
        Schema::table('payments', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['payment_method_id']);
        });

        // Now re-add the correct foreign key referencing the correct column in the restaurant_payment_methods table
        Schema::table('payments', function (Blueprint $table) {
            // Add the new foreign key
            $table->foreign('payment_method_id')
                ->references('payment_method_id') // Correct reference column
                ->on('restaurant_payment_methods')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes: drop the new foreign key and re-add the old one
        Schema::table('payments', function (Blueprint $table) {
            // Drop the newly added foreign key
            $table->dropForeign(['payment_method_id']);

            // Optionally, re-add the old foreign key if needed
            $table->foreign('payment_method_id')
                ->references('id') // Old reference (assuming it was on id)
                ->on('restaurant_payment_methods')
                ->onDelete('cascade');
        });
    }
};
