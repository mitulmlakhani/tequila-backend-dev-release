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
            $table->decimal('shift_tip_amount', 10, 2)->nullable()->after('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_logs', function (Blueprint $table) {
            $table->dropColumn('shift_tip_amount'); 
        });
    }
};
