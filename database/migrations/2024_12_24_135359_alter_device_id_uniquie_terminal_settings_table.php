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
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->dropUnique(['device_id']); // Remove the unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->unique('device_id'); // Re-add the unique constraint
        });
    }
};
