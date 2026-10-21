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
        Schema::table('ingredient_inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id');
        });

        Schema::table('ingredient_inventory_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id');
        });

        Schema::table('inventory_details', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredient_inventories', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });

        Schema::table('ingredient_inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });

        Schema::table('inventory_details', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
};