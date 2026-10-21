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
        Schema::create('restaurant_payroll_configs', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Restaurant::class)->nullable()
                ->constrained()->onDelete('set null');

            $table->enum('payout_frequency', [
                'weekly',
                'monthly'
            ]);

            $table->tinyInteger('payout_day')->nullable();
            $table->string('payroll_payout_time')->default('00:00');

            // State
            $table->string('state_code', 2);
            $table->boolean('federal_tax_enabled')->default(true);
            $table->decimal('federal_tax_rate', 10, 4)->default(0);

            // Employer taxes
            $table->decimal('social_security_employer_rate', 10, 4)->default(6.20);
            $table->decimal('social_security_cap', 10, 4)->default(6.20);

            $table->decimal('medicare_employer_rate', 10, 4)->default(1.45);

            $table->decimal('futa_rate', 10, 4)->default(0.0060);
            $table->decimal('futa_wage_cap', 10, 2)->default(7000);

            $table->decimal('suta_rate', 10, 4)->default(2.70);
            $table->decimal('suta_wage_cap', 10, 2)->default(9000);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_payroll_configs');
    }
};
