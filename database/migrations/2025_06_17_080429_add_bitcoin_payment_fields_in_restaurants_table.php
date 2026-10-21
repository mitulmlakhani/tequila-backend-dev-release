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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('bitcoin_payment_address')->nullable()->after('is_cc_fee_show');
            $table->string('bitcoin_payment_fees')->nullable()->after('bitcoin_payment_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['bitcoin_payment_address', 'bitcoin_payment_fees']);
        });
    }
};
