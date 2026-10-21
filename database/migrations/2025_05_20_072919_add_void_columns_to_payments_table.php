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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'void_pax_response')) {
                $table->text('void_pax_response')->nullable()->after('pax_response');
            }

            if (!Schema::hasColumn('payments', 'is_voided')) {
                $table->boolean('is_voided')->default(0)->after('is_post_auth_done');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'void_pax_response')) {
                $table->dropColumn('void_pax_response');
            }

            if (Schema::hasColumn('payments', 'is_voided')) {
                $table->dropColumn('is_voided');
            }
        });
    }
};
