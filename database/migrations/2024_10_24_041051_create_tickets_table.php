<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('ticket_number')->unique();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('open'); // E.g. 'open', 'paid', 'cancelled'
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
