<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservation_customers', function (Blueprint $table) {
            $table->bigInteger('local_id')->unsigned()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_customers', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
};
