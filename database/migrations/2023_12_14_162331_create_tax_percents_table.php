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
        Schema::create('tax_percents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tax_master_id')->unsigned();
            $table->string('sub_tax_name')->nullable();
            $table->string('tax_percent'); 
            $table->enum('default',["0","1"])->default("0")->comment('0->"blank" 1->default Tax');           
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('tax_master_id')->on('tax_masters')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_percents');
    }
};
