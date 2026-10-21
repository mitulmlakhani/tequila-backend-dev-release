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
            $table->foreignIdFor(\App\Models\ShiftReport::class)->after('id')->nullable()->constrained();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\ShiftReport::class)->after('id')->nullable()->constrained();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\ShiftReport::class)->after('id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\ShiftReport::class);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\ShiftReport::class);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\ShiftReport::class);
        });
    }
};
