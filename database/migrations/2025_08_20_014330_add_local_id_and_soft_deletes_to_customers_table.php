<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('customers', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'local_id')) {
                $table->dropColumn('local_id');
            }
            if (Schema::hasColumn('customers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
