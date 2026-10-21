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
        Schema::table('shift_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('started_by')->nullable()->after('id');
            $table->unsignedBigInteger('ended_by')->nullable()->after('started_by');
    
            $table->foreign('started_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ended_by')->references('id')->on('users')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('shift_reports', function (Blueprint $table) {
            $table->dropForeign(['started_by']);
            $table->dropForeign(['ended_by']);
            $table->dropColumn(['started_by', 'ended_by']);
        });
    }    
};
