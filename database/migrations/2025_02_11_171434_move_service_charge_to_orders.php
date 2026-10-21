<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('service_charge', 10, 2)->default(0)->after('total_amount');
            $table->enum('service_charge_type', ['percentage', 'amount'])->default('percentage')->after('service_charge');
        });

        Schema::table('parent_orders', function (Blueprint $table) {
            $table->dropColumn('service_charge');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['service_charge', 'service_charge_type']);
        });

        Schema::table('parent_orders', function (Blueprint $table) {
            $table->decimal('service_charge', 10, 2)->default(0)->after('total_amount');
        });
    }
};

