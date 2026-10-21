<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteAndLocalIdToRestaurantAddressTable extends Migration
{
    public function up()
    {
        Schema::table('restaurant_address', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_address', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('restaurant_address', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('restaurant_address', function (Blueprint $table) {
            if (Schema::hasColumn('restaurant_address', 'local_id')) {
                $table->dropColumn('local_id');
            }
            if (Schema::hasColumn('restaurant_address', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
