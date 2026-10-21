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
        Schema::table('parent_orders', function (Blueprint $table) {
            $table->string('reference_number')->unique()->nullable()->after('id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('reference_number')->unique()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_orders', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
};
