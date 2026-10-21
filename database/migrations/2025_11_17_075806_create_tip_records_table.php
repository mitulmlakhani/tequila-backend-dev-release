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
        Schema::create('tip_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants');
            $table->foreignId('employee_id');
            $table->foreignId('shift_id')->nullable();
            $table->unsignedBigInteger('tip_setting_id')->nullable();
            $table->unsignedBigInteger('pool_id')->nullable();
            $table->decimal('tip_amount', 10, 2);
            $table->date('tip_date');
            $table->timestamp('tip_date_time')->nullable();
            $table->enum('tip_type', ['tip_share', 'tip_pool', 'normal_tip'])->nullable();
            $table->string('tip_payment_method')->nullable();

            $table->foreignId('tip_from_user_id')->nullable();
            $table->decimal('total_tip_amount', 10, 2)->nullable();
            $table->enum('tip_value_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('tip_value', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tip_records');
    }
};
