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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('banner_img', 255)->nullable();
            $table->string('locations', 255)->nullable();
            $table->json('opening_hours')->nullable();
            $table->string('banner_subtitle', 255)->nullable();
            $table->string('banner_title', 255)->nullable();
            $table->string('banner_desc', 255)->nullable();
            $table->string('banner_link', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('phone_no', 15)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('facebook')->nullable();
            $table->string('x')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();

            $table->boolean('status')->default(true);
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
