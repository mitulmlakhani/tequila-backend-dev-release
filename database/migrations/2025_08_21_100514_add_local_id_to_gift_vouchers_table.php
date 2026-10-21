<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdToGiftVouchersTable extends Migration
{
    public function up()
    {
        Schema::table('gift_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('gift_vouchers', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }
        });
    }

    public function down()
    {
        Schema::table('gift_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('gift_vouchers', 'local_id')) {
                $table->dropColumn('local_id');
            }
        });
    }
}
