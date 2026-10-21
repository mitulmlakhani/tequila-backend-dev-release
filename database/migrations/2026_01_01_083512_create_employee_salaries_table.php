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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Restaurant::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');

            $table->decimal('salary_amount', 10, 2)->default(0);

            $table->decimal('federal_income_tax', 10, 4)->default(0);   
            $table->decimal('social_security_employee', 10, 4)->default(0);
            $table->decimal('medicare_employee', 10, 4)->default(0);
            $table->decimal('state_income_tax', 10, 4)->default(0);

            $table->decimal('social_security_employer', 10, 4)->default(0);
            $table->decimal('medicare_employer', 10, 4)->default(0);

            $table->decimal('futa_tax', 10, 4)->default(0);
            $table->decimal('suta_tax', 10, 4)->default(0);
            $table->decimal('total_employer_tax', 10, 4)->default(0);
            $table->decimal('total_employee_tax', 10, 4)->default(0);
            $table->decimal('net_salary_amount', 10, 4)->default(0);

            $table->date('salary_from_date');
            $table->date('salary_to_date');

            $table->enum('status', ['draft', 'finalized'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
