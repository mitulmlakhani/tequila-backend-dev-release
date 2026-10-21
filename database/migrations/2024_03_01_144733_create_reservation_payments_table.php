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
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reservation_customers_id')->unsigned();
            $table->bigInteger('reservation_id')->unsigned();
            $table->enum('pay_type',["0","1","2","3","4"])->comment('1->COD, 2->Debit/Credit, 3->UPI, 4->NetBanking')->default("0"); 
            $table->enum('pay_status',["1","2"])->comment('1->paid, 2->refund')->default("1");
            $table->decimal('amount', 11, 2);
            $table->decimal('discount', 11, 2)->nullable();
            $table->tinyInteger('discount_pct')->nullable()->comment('discount percentage %');            
            $table->decimal('gift_amt', 11, 2)->nullable();
            $table->tinyInteger('gift_pct')->nullable()->comment('Gift percentage %');
            $table->decimal('final_amount', 11, 2);                                        
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
        Schema::dropIfExists('reservation_payments');
    }
};
