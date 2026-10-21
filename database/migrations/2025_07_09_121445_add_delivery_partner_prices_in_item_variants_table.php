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
            $table->decimal('door_dash_price', 8, 2)->nullable()->after('price');
            $table->decimal('ubereats_price', 8, 2)->nullable()->after('door_dash_price');
            $table->decimal('grubhub_price', 8, 2)->nullable()->after('ubereats_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_variants', function (Blueprint $table) {
            $table->dropColumn([
                'door_dash_price',
                'ubereats_price',
                'grubhub_price',
            ]);
        });
    }
};
