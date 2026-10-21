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
        Schema::table('shift_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('user_id');
            $table->enum('payroll_type', ['hourly', 'salary'])->default('hourly')->after('timestamp');
            $table->decimal('payroll_amount', 10, 2)->nullable()->after('payroll_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_logs', function (Blueprint $table) {
            $table->dropColumn([
                'role_id',
                'payroll_type',
                'payroll_amount',
            ]);
        });
    }
};
