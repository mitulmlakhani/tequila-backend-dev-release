<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeleteToTaxesTable extends Migration
{
    public function up()
    {
        Schema::table('taxes', function (Blueprint $table) {
            if (!Schema::hasColumn('taxes', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('taxes', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('taxes', function (Blueprint $table) {
            if (Schema::hasColumn('taxes', 'local_id')) {
                $table->dropColumn('local_id');
            }

            if (Schema::hasColumn('taxes', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
