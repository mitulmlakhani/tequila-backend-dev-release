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
        Schema::create('reservation_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('branch_id')->nullable(); 
            $table->string('email')->nullable();
            $table->string('name')->nullable(); 
            $table->string('mobile')->nullable();
            $table->enum('otp_verification',["0","1"])->default("0");
            $table->enum('email_verification',["0","1"])->default("0");
            $table->string('address')->nullable();
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active')->default("1");
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_customers');
    }
};
