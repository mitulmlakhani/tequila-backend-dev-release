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
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('secret_key');
            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by', 'status']);
        });
    }
};
