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
        Schema::table('item_modifier_group_details', function (Blueprint $table) {
            if (!Schema::hasColumn('item_modifier_group_details', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_modifier_group_details', function (Blueprint $table) {
            if (Schema::hasColumn('item_modifier_group_details', 'local_id')) {
                $table->dropColumn('local_id');
                $table->dropSoftDeletes();
            }
        });
    }
};
