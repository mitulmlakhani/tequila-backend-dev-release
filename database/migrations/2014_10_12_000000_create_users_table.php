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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('application_type')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('status')->default(false);
            $table->rememberToken();
            $table->string('image')->nullable();
            $table->integer('user_type')->nullable()->comment("[1=>Tequilauser, 2=>RestaurantUser]");
            $table->foreignId('restaurant_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['restaurant_id', 'email'], 'users_restaurant_email_unique');
            $table->unique(['restaurant_id', 'mobile'], 'users_restaurant_mobile_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
