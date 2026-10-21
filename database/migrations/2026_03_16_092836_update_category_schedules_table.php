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
        Schema::table('category_schedules', function (Blueprint $table) {
            $table->dropColumn('from_time');
            $table->dropColumn('to_time');

            $table->json('schedule')->nullable()->after('days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_schedules', function (Blueprint $table) {
            $table->time('from_time')->nullable()->after('days');
            $table->time('to_time')->nullable()->after('from_time');
            $table->dropColumn('schedule');
        });
    }
};
