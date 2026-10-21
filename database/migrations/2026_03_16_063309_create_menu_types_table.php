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
        Schema::create('menu_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->string('name');
            $table->string('color');
            $table->string('font_color');
            $table->tinyInteger('index');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('menucategorys', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_type_id')->nullable()->after('id');
            $table->foreign('menu_type_id')->references('id')->on('menu_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menucategorys', function (Blueprint $table) {
            $table->dropForeign(['menu_type_id']);
            $table->dropColumn('menu_type_id');
        });

        Schema::dropIfExists('menu_types');
    }
};
