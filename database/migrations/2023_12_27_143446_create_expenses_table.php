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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('expenses_type_id')->unsigned();
            $table->string('expense_category');  
            $table->decimal('expense_amount', 11, 2);  
            $table->longText('expense_description');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');                       
            $table->enum('status',["0","1"])->default('0')->comment('0->Inactive 1->Active');
            $table->timestamps();           
            $table->softDeletes();
            $table->foreign('expenses_type_id')->on('expenses_types')->references('id')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
