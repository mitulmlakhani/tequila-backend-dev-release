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
            // Modify the column to integer, allowing NULL temporarily
            DB::statement('ALTER TABLE order_items MODIFY status INT NULL DEFAULT NULL');
            
            // Set a default value if necessary
            DB::statement('UPDATE order_items SET status = 1 WHERE status IS NULL');

            // Make the column NOT NULL with a default value
            DB::statement('ALTER TABLE order_items MODIFY status INT NOT NULL DEFAULT 1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Revert the column back to VARCHAR
            DB::statement('ALTER TABLE order_items MODIFY status VARCHAR(255) NULL DEFAULT NULL');
        });
    }
};
