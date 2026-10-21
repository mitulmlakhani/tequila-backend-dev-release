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
            $table->string('cc_fee_label')->nullable()->after('status');
            $table->string('cc_fee')->nullable()->after('cc_fee_label');
            $table->string('cd_label')->nullable()->after('cc_fee');
            $table->boolean('is_cc_fee_applicable')->nullable()->default(false)->after('cd_label');
            $table->boolean('is_cc_fee_show')->nullable()->default(false)->after('is_cc_fee_applicable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function(Blueprint $table)
        {
            
        });
    }
};
