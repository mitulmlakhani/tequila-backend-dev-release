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
        Schema::table("website_settings", function (Blueprint $table) {
            $table->string("about_title")->nullable()->after("banner_link");
            $table->text("about_description")->nullable()->after("about_title");
            $table->string("chef_name")->nullable()->after("about_description");
            $table->string("chef_sub_title")->nullable()->after("chef_name");
            $table->text("chef_description")->nullable()->after("chef_sub_title");
            $table->string("chef_image")->nullable()->after("chef_description");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("website_settings", function (Blueprint $table) {
            $table->dropColumn("about_title");
            $table->dropColumn("about_description");
            $table->dropColumn("chef_name");
            $table->dropColumn("chef_sub_title");
            $table->dropColumn("chef_description");
            $table->dropColumn("chef_image");
        });
    }
};
