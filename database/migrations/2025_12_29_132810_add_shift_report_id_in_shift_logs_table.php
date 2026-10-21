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
            $table->decimal('overtime_amount', 10, 2)->nullable()->after('payroll_amount');
            $table->integer('overtime_hours_after')->nullable()->after('overtime_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_logs', function (Blueprint $table) {
            $table->dropColumn([
                'overtime_amount',
                'overtime_hours_after',
            ]);
        });
    }
};
