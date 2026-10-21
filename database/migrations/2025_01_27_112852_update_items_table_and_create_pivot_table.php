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
            // Drop the foreign key and column for pos_device_id
            if (Schema::hasColumn('items', 'pos_device_id')) {
                $table->dropForeign(['pos_device_id']);
                $table->dropColumn('pos_device_id');
            }
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('kitchen_section_id')->nullable()->constrained('kitchen_sections')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add the pos_device_id column back
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('pos_device_id')->nullable()->constrained('pos_devices')->onDelete('set null');
        });

        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'kitchen_section_id')) {
                $table->dropForeign(['kitchen_section_id']);
                $table->dropColumn('kitchen_section_id');
            }
        });
    }
};
