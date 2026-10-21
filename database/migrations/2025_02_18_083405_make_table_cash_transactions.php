<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('cascade'); // Cashier performing transaction
            $table->decimal('amount', 10, 2);
            $table->string('transaction_type'); // 'cash_in' or 'cash_out'
            $table->text('comment')->nullable();
            $table->foreignId('cash_out_to')->nullable()->constrained('users')->onDelete('set null'); // Waiter/Cashier (Only for cash_out_type=1)
            $table->string('vendor_name')->nullable(); // Required when cash_out_type=2
            $table->enum('cash_out_type', ['tip_out', 'vendor'])->nullable(); // 1 - TipOut, 2 - Vendor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_transactions');
    }
};
