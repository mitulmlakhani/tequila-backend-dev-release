<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeleteToRestaurantPaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::table('restaurant_payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_payment_methods', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('restaurant_payment_methods', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('restaurant_payment_methods', function (Blueprint $table) {
            if (Schema::hasColumn('restaurant_payment_methods', 'local_id')) {
                $table->dropColumn('local_id');
            }

            if (Schema::hasColumn('restaurant_payment_methods', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
