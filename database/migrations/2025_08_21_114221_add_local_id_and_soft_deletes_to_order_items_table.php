<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('order_items', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'local_id')) {
                $table->dropColumn('local_id');
            }

            if (Schema::hasColumn('order_items', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
