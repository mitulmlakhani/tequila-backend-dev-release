<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToExpensesTypesTable extends Migration
{
    public function up()
    {
        Schema::table('expenses_types', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses_types', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('expenses_types', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('expenses_types', function (Blueprint $table) {
            if (Schema::hasColumn('expenses_types', 'local_id')) {
                $table->dropColumn('local_id');
            }
            if (Schema::hasColumn('expenses_types', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
