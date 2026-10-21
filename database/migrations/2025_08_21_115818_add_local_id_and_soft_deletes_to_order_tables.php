<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToOrderTables extends Migration
{
    public function up()
    {
        Schema::table('order_tables', function (Blueprint $table) {
            if (!Schema::hasColumn('order_tables', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('order_tables', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('order_tables', function (Blueprint $table) {
            if (Schema::hasColumn('order_tables', 'local_id')) {
                $table->dropColumn('local_id');
            }

            if (Schema::hasColumn('order_tables', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
