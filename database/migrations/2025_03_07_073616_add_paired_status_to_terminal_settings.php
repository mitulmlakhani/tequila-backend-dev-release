<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPairedStatusToTerminalSettings extends Migration
{
    public function up()
    {
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->boolean('paired_status')->default(0)->after('status'); // 0 = Vacant, 1 = Paired
        });
    }

    public function down()
    {
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->dropColumn('paired_status');
        });
    }
}
