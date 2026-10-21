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
        Schema::table('tickets', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['order_id']);
            $table->dropForeign(['staff_id']);
        });

        // Drop the tickets table
        Schema::dropIfExists('tickets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
};
