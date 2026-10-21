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
        Schema::table('report_notification_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id');
            $table->unsignedBigInteger('user_id')->nullable()->after('restaurant_id');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_notification_schedules', function (Blueprint $table) {
            $table->dropColumn('local_id');
            $table->dropColumn('user_id');
            $table->dropSoftDeletes();
        });
    }
};
