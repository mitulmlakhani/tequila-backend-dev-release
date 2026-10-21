<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSplitTrackingToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_split')->default(false)->after('portion_price');
            $table->string('selected_split_type')->nullable()->after('is_split'); // e.g. 'even', 'custom', 'item'
            $table->unsignedBigInteger('parent_order_item_id')->nullable()->after('selected_split_type'); // links to original
            $table->integer('splited_count')->nullable()->after('parent_order_item_id'); // how many times this was split
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['is_split', 'selected_split_type', 'parent_order_item_id', 'splited_count']);
        });
    }
}
