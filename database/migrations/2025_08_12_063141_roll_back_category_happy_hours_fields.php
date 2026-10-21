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
        Schema::table('menucategorys', function (Blueprint $table) {
            $table->dropColumn(['is_happy_hour', 'happy_hour_days', 'happy_hour_times']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menucategorys', function (Blueprint $table) {
            $table->boolean('is_happy_hour')->default(false)->after('status');
            $table->json('happy_hour_days')->nullable()->after('is_happy_hour');
            $table->string('happy_hour_times')->nullable()->after('happy_hour_days');
        });
    }
};
