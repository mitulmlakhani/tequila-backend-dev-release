<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('restaurant_address', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->after('restaurant_id'); // Add title field
        });
    }

    public function down()
    {
        Schema::table('restaurant_address', function (Blueprint $table) {
            $table->dropColumn('title'); // Drop title field if rolling back
        });
    }
};
