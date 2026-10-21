<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('pax_terminal_id')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->boolean('is_deposit')->default(0);
            $table->string('card_number')->nullable();
            $table->string('card_name')->nullable();
            $table->string('amex')->nullable();
            $table->string('approval_code')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('terminal_sn')->nullable();
            $table->string('batch_number')->nullable();
            $table->boolean('is_post_auth_done')->default(false);
            $table->boolean('is_voided')->default(false);
            $table->decimal('tip_amount', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'pax_terminal_id', 'cashier_id', 'is_deposit', 'card_number', 'amex',
                'approval_code', 'reference_number', 'terminal_sn', 'batch_number',
                'is_post_auth_done', 'tip_amount', 'card_name', 'is_voided'
            ]);
        });
    }
};