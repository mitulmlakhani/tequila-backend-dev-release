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
        Schema::table('shift_reports', function (Blueprint $table) {
            $table->boolean('autoclose_shift')->default(true)->after('end_time');
            $table->timestamp('autoclose_at')->nullable()->after('autoclose_shift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_reports', function (Blueprint $table) {
            $table->dropColumn(['autoclose_shift', 'autoclose_at']);
        });
    }
};
