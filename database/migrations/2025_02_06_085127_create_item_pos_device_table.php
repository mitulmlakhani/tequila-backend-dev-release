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

        Schema::create('item_pos_device', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('pos_device_id')->constrained()->onDelete('cascade');
            $table->timestamps();
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

        Schema::dropIfExists('item_pos_device');
    }
};
