<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\ShiftReport::class)->after('id')->nullable()->constrained();
        });

        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\ShiftReport::class)->after('local_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropColumn('local_id');
            $table->dropConstrainedForeignIdFor(\App\Models\ShiftReport::class);
        });

        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\ShiftReport::class);
        });
    }
};
