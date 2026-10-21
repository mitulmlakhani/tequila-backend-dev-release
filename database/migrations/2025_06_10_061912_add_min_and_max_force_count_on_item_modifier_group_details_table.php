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
        Schema::table('item_modifier_group_details', function (Blueprint $table) {
            $table->tinyInteger('min_modifier_count')->nullable();
            $table->tinyInteger('max_modifier_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_modifier_group_details', function (Blueprint $table) {
            $table->dropColumn(['min_modifier_count', 'max_modifier_count']);
        });
    }
};
