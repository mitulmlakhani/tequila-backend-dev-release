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
        Schema::table('items', function (Blueprint $table) {
            // Drop the kitchen_section_id column
            if (Schema::hasColumn('items', 'kitchen_section_id')) {
                $table->dropForeign(['kitchen_section_id']);
                $table->dropColumn('kitchen_section_id');
            }

            // Reintroduce pos_device_id
            $table->foreignId('pos_device_id')->nullable()->constrained('pos_devices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Remove pos_device_id
            $table->dropForeign(['pos_device_id']);
            $table->dropColumn('pos_device_id');

            // Reintroduce kitchen_section_id
            $table->foreignId('kitchen_section_id')->nullable()->constrained('kitchen_sections')->onDelete('set null');
        });
    }
};
