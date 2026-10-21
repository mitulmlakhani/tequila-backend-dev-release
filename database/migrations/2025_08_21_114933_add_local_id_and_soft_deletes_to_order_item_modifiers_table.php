<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToOrderItemModifiersTable extends Migration
{
    public function up()
    {
        Schema::table('order_item_modifiers', function (Blueprint $table) {
            if (!Schema::hasColumn('order_item_modifiers', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('order_item_modifiers', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('order_item_modifiers', function (Blueprint $table) {
            if (Schema::hasColumn('order_item_modifiers', 'local_id')) {
                $table->dropColumn('local_id');
            }

            if (Schema::hasColumn('order_item_modifiers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
