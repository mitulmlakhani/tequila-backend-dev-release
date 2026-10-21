<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gift_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->string('code')->unique();
            $table->decimal('amount', 8, 2);
            $table->date('expiry_date');
            $table->enum('status', ['active', 'expired', 'redeemed', 'inactive'])->default('active');
            $table->unsignedBigInteger('restaurant_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gift_vouchers');
    }
};
