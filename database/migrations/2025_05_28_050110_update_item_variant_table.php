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
        Schema::table('item_variants', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('name');
            $table->string('description')->nullable()->after('sku');
            $table->boolean('is_default')->default(false)->after('image');
            $table->tinyInteger('order_index')->default(0)->after('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_variants', function (Blueprint $table) {
            $table->dropColumn(['description', 'sku', 'is_default', 'order_index']);
        });
    }
};
