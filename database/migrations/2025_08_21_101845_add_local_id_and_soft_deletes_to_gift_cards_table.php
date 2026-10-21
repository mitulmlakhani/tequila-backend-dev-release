<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdAndSoftDeletesToGiftCardsTable extends Migration
{
    public function up()
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            if (!Schema::hasColumn('gift_cards', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('gift_cards', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            if (Schema::hasColumn('gift_cards', 'local_id')) {
                $table->dropColumn('local_id');
            }

            if (Schema::hasColumn('gift_cards', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
