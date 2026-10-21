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
            $table->unsignedBigInteger('item_tag_id')->nullable()->after('id');
            $table->foreign('item_tag_id')->references('id')->on('item_tags')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menucategorys', function (Blueprint $table) {
            $table->dropForeign(['item_tag_id']);
            $table->dropColumn('item_tag_id');
        });
    }
};
