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
        Schema::table('tip_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('tip_percentages', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('tip_pools', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('tip_pool_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('tip_records', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tip_settings', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('tip_percentages', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('tip_pools', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('tip_pool_employees', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('tip_records', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
};
