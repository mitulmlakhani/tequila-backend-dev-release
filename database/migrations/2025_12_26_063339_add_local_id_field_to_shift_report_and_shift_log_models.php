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
        Schema::table('shift_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });

        Schema::table('shift_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_reports', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });

        Schema::table('shift_logs', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
};
