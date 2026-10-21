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
        Schema::table('parent_orders', function (Blueprint $table) {
            $table->enum('delivery_partner', ['ubereats', 'doordash', 'grubhub'])->nullable()->after('order_type');
            $table->string('delivery_partner_order_id')->nullable()->unique()->after('delivery_partner');
            $table->unsignedBigInteger('staff_id')->nullable()->change();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_partner_order_id', 'delivery_partner']);
            $table->unsignedBigInteger('staff_id')->nullable(false)->change();
        });
    }
};
