<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_w4_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Restaurant::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');

            $table->enum('filing_status', [
                'single',
                'married_joint',
                'head_of_household'
            ]);

            $table->boolean('multiple_jobs')->default(false);

            $table->unsignedInteger('dependents_under_17')->default(0);
            $table->unsignedInteger('dependents_other')->default(0);
            $table->decimal('dependents_credit_amount', 10, 2)->default(0);

            $table->decimal('other_income', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('extra_withholding', 10, 2)->default(0);

            $table->date('signed_at');
            $table->date('effective_from');

            $table->json('w4_payload')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_w4_forms');
    }
};
