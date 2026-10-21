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
        Schema::table('shift_logs', function (Blueprint $table) {
            $table->string('reference_no')->nullable();
            $table->date('business_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_logs', function (Blueprint $table) {
            $table->dropColumn(['is_processed', 'reference_no', 'business_date']);
        });
    }
};
