<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('payments', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'local_id')) {
                $table->dropColumn('local_id');
            }
            if (Schema::hasColumn('payments', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
