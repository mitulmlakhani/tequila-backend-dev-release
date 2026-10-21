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
        Schema::create('tax_sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('session');
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');

        });
    }
    /**
     * Reverse the migrations test.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_sessions');
    }
};
