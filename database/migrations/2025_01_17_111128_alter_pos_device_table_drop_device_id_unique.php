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
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->dropUnique(['device_id']); // Remove the existing unique constraint
        });

        Schema::table('pos_devices', function (Blueprint $table) {
            $table->unique(['device_id', 'deleted_at']); // Make it unique with deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->dropUnique(['device_id', 'deleted_at']); // Remove the new constraint
            $table->unique('device_id'); // Restore the original unique constraint
        });
    }
};
