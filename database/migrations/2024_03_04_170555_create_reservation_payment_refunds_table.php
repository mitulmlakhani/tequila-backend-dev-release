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
        Schema::create('reservation_payment_refunds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reservation_customers_id')->unsigned();
            $table->bigInteger('reservation_id')->unsigned();
            $table->enum('refund_pay',["0","1","2"])->comment('1->fullAmt, 2->partlyAmt')->default("0");
            $table->decimal('amount', 11, 2)->nullable();
            $table->decimal('partly_amt', 11, 2)->nullable();
            $table->tinyInteger('refund_pct')->nullable()->comment('refund percentage %');
            $table->decimal('refund_amount', 11, 2);
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active')->default("0");
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();           
            $table->foreign('reservation_id')->on('reservations')->references('id')->onDelete('cascade');
            $table->foreign('reservation_customers_id')->on('reservation_customers')->references('id')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_payment_refunds');
    }
};
