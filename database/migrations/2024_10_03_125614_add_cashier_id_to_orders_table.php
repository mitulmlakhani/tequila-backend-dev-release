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
            // Assuming 'cashier_id' references the 'id' in the 'users' table
            $table->foreignId('cashier_id')
                  ->nullable()  // Assuming that the cashier_id can be nullable initially
                  ->after('staff_id')
                  ->constrained('users')  // Foreign key constraint with the users table
                  ->onDelete('set null');  // Set to null if the referenced user is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key and the column
            $table->dropForeign(['cashier_id']);
            $table->dropColumn('cashier_id');
        });
    }
};
