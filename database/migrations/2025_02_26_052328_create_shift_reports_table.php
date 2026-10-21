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
        Schema::create('shift_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Cashier ID
            $table->unsignedBigInteger('restaurant_id');
            $table->decimal('opening_amount', 10, 2);
            $table->decimal('closing_amount', 10, 2)->nullable();
            $table->decimal('cashout', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->nullable(); // Closing - Opening
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->enum('status', [1, 2])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_reports');
    }
};
