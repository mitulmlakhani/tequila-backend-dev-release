<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToParentOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('parent_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('parent_orders', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('parent_orders', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('parent_orders', function (Blueprint $table) {
            if (Schema::hasColumn('parent_orders', 'local_id')) {
                $table->dropColumn('local_id');
            }
            if (Schema::hasColumn('parent_orders', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
