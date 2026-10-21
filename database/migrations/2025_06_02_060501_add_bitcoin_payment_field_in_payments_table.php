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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('crypto_currency')->nullable();
            $table->string('crypto_address')->nullable();
            $table->string('crypto_amount')->nullable();
            $table->string('tx_hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'crypto_currency',
                'crypto_address',
                'crypto_amount',
                'tx_hash'
            ]);
        });
    }
};
