<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():   void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('tip_transaction_fees', 10, 2)->default(0.00)->after('tip_amount');
            $table->decimal('tip_gross_amount', 10, 2)->default(0.00)->after('tip_transaction_fees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('tip_transaction_fees');
            $table->dropColumn('tip_gross_amount');
        }); 
    }
};
