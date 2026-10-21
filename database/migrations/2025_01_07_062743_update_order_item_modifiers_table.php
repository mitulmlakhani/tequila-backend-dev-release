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
        Schema::table('order_item_modifiers', function (Blueprint $table) {
            $table->unsignedBigInteger('modifier_id')->nullable()->after('order_item_id');
            $table->unsignedBigInteger('modifier_group_id')->nullable()->after('modifier_id');
            $table->boolean('is_open_modifier')->default(false)->after('modifier_group_id');
            $table->boolean('is_open_modifier_group')->default(false)->after('is_open_modifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_modifiers', function (Blueprint $table) {
            $table->dropColumn('modifier_id');
            $table->dropColumn('modifier_group_id');
            $table->dropColumn('is_open_modifier');
            $table->dropColumn('is_open_modifier_group');
        });
    }
};
