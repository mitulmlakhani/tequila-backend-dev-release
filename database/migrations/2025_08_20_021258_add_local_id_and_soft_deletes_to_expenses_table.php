<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToExpensesTable extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'local_id')) {
                $table->dropColumn('local_id');
            }
        });
    }
}
