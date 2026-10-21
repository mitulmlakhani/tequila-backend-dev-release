<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->text('pax_response')->nullable();
            $table->string('pax_terminal_id')->nullable();
            $table->string('card_number')->nullable();
            $table->string('amex')->nullable();
            $table->string('approval_code')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('terminal_sn')->nullable();
            $table->string('batch_number')->nullable();
            $table->boolean('is_post_auth_done')->default(false);
            $table->decimal('tip_amount', 10, 2)->default(0.00);
            $table->string('card_name')->nullable();
            $table->boolean('is_voided')->default(false);
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->foreign('device_id')->references('id')->on('pos_devices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->dropForeign(['device_id']);
            $table->dropColumn([
                'pax_response',
                'pax_terminal_id',
                'card_number',
                'amex',
                'approval_code',
                'reference_number',
                'terminal_sn',
                'batch_number',
                'is_post_auth_done',
                'tip_amount',
                'card_name',
                'is_voided',
                'device_id',
            ]);
        });
    }
};
