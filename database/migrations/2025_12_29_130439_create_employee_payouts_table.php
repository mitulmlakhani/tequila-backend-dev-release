<?php

use App\Models\Restaurant;
use App\Models\User;
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
        Schema::create('employee_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class);
            $table->foreignIdFor(User::class);
            $table->unsignedBigInteger(column: 'role_id');
            $table->decimal('payroll_amount', 10, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('overtime_hours_after', 10, 2)->default(0);
            $table->decimal('normal_hours', 10, 2)->default(0);
            $table->decimal('overtime_hours', 10, 2)->default(0);
            $table->decimal('tip_amount', 10, 2)->default(0);
            $table->decimal('payout_amount', 10, 2)->default(0);
            $table->date('payout_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payouts');
    }
};
