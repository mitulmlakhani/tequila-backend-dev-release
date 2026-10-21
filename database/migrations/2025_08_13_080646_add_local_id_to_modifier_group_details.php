<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalIdToModifierGroupDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modifier_group_details', function (Blueprint $table) {
            // Add the local_id column
            $table->unsignedBigInteger('local_id')->nullable()->after('id');
            
            // Create index on local_id for faster lookup
            $table->index('local_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modifier_group_details', function (Blueprint $table) {
            // Remove the local_id column and the index if rolling back
            $table->dropIndex(['local_id']);
            $table->dropColumn('local_id');
        });
    }
}
