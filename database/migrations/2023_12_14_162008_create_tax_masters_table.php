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
        Schema::create('tax_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tax_sessions_id')->unsigned();
            $table->string('tax_name')->nullable();                        
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active');
            $table->timestamps();           
            $table->softDeletes();
            $table->foreign('tax_sessions_id')->on('tax_sessions')->references('id')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_masters');
    }
};
