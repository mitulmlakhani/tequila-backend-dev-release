<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->bigInteger('local_id')->unsigned()->nullable()->after('id')->index();
            $table->softDeletes(); // adds deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->dropColumn('local_id');
            $table->dropSoftDeletes();
        });
    }
};
