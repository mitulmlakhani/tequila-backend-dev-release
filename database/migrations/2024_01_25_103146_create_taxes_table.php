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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('branch_id')->nullable();
            $table->string('tax_name');
            $table->decimal('tax_percent', 11, 2)->nullable();
            $table->string('category_id')->nullable();
            $table->enum('status',["0","1"])->comment('0->Inactive 1->Active')->default("0");
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
        Schema::dropIfExists('taxes');
    }
};
