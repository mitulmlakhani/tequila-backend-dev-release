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
        Schema::create('floor_item_icons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->default(DB::raw('(UUID())'))->unique();
            $table->string('name');
            $table->string('icon');
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('floor_item_icon_id')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floor_item_icons');
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('floor_item_icon_id');
        });
    }
};
