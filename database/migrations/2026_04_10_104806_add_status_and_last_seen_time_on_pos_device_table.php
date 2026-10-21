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
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->boolean('connection_status')->default(0)->after('ip_address');
            $table->timestamp('last_seen')->nullable()->after('status');
        });

        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->boolean('connection_status')->default(0)->after('ip_address');
            $table->timestamp('last_seen')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->dropColumn(['connection_status', 'last_seen']);
        });

        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->dropColumn(['connection_status', 'last_seen']);
        });
    }
};
