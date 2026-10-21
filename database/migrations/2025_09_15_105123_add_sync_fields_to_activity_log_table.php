<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSyncFieldsToActivityLogTable extends Migration
{
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Track last synced time locally
            $table->timestamp('updated_at_local')->nullable()->after('updated_at')->comment('Last synced time to cloud');
            $table->bigInteger('local_id')->unsigned()->nullable()->after('id');
            // Optional: Add soft deletes if not already present
            if (!Schema::hasColumn('activity_log', 'deleted_at')) {
                $table->softDeletes()->after('updated_at_local');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('updated_at_local');
            $table->dropColumn('local_id');
            if (Schema::hasColumn('activity_log', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
