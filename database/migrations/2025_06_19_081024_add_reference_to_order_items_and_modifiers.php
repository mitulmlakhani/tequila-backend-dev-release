<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceToOrderItemsAndModifiers extends Migration
{
    public function up()
    {
        // Add reference_number to order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('item_variant_id')->index();
        });

        // Add order_item_reference_number to order_item_modifiers
        Schema::table('order_item_modifiers', function (Blueprint $table) {
            $table->string('order_item_reference_number')->nullable()->after('order_item_id')->index();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });

        Schema::table('order_item_modifiers', function (Blueprint $table) {
            $table->dropColumn('order_item_reference_number');
        });
    }
}
