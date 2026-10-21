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
        Schema::table('parent_orders_and_orders', function (Blueprint $table) {
            Schema::table('parent_orders', function (Blueprint $table) {
                $table->text('note')->nullable()->after('taxes');
            });
    
            Schema::table('orders', function (Blueprint $table) {
                $table->text('note')->nullable()->after('taxes');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_orders_and_orders', function (Blueprint $table) {
            Schema::table('parent_orders', function (Blueprint $table) {
                $table->dropColumn('note');
            });
    
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('note');
            });
        });
    }
};
