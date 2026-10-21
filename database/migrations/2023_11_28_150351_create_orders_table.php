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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('table_id')->nullable()->references('id')->on('tables')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('payment_method_id')->nullable()->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreignId('order_status_id')->nullable()->references('id')->on('order_statuses')->onDelete('cascade');
            $table->string('total_amount')->nullable();
            $table->string('tax_amount')->nullable();
            $table->boolean('is_tax_exempt')->default(false);
            $table->string('taxes')->nullable();
            $table->string('order_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('credit_card_fee_info')->nullable();
            $table->string('tip_info')->nullable();
            $table->boolean('is_pay_by_cc')->default(false);
            $table->string('table_no')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('staff_name')->nullable();
            $table->string('staff_email')->nullable();
            $table->string('staff_role')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
