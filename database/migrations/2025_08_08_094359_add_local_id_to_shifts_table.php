<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdToShiftsTable extends Migration
{
    public function up()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
    }

    public function down()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
}
