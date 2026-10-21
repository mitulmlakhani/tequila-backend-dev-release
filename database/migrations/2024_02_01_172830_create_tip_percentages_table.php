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
        Schema::create('tip_percentages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('branch_id')->nullable();
            $table->string('tip_suggestion_label')->nullable();
            $table->decimal('set_tip_one', 11, 2)->nullable();
            $table->decimal('set_tip_two', 11, 2)->nullable();
            $table->decimal('set_tip_three', 11, 2)->nullable();
            $table->decimal('set_tip_four', 11, 2)->nullable();
            $table->decimal('set_tip_five', 11, 2)->nullable();
            $table->decimal('set_tip_six', 11, 2)->nullable();
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
        Schema::dropIfExists('tip_percentages');
    }
};
