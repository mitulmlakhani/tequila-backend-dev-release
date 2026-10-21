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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reservation_customers_id')->unsigned();
            $table->string('booking_id');
            $table->date('date');
            $table->time('time')->default('00:00');
            $table->time('food_time')->default('00:00');
            $table->tinyInteger('party');
            $table->string('table');            
            $table->bigInteger('bonus_points')->nullable();            
            $table->string('gift_card_number')->nullable();            
            $table->enum('party_confirm',["0","1","2","3","4","5","6"])->comment('0.not arrived,1.confirm,2.arrived,3.payPending,4.pending,5.closed,6.tresh')->default(null);        
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active')->default("0");
            $table->text('message')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();           
            $table->foreign('reservation_customers_id')->on('reservation_customers')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
