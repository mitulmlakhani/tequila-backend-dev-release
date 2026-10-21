<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('item_modifier_groups', function (Blueprint $table) {
            // Add deleted_at for soft deletes
            if (!Schema::hasColumn('item_modifier_groups', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('updated_at')->index();
            }
        });
    }

    public function down()
    {
        Schema::table('item_modifier_groups', function (Blueprint $table) {
            if (Schema::hasColumn('item_modifier_groups', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }
};
