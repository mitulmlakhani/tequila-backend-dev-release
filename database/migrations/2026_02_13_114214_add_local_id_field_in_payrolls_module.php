<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurant_payroll_configs', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('employee_payouts', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('employee_payrolls', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
        Schema::table('employee_w4_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_payroll_configs', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('employee_payouts', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('employee_payrolls', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
        Schema::table('employee_w4_forms', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
};
