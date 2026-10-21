<?php

use App\Models\Restaurant;
use App\Models\User;
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
        Schema::create('category_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class)->constrained();
            $table->string('title');
            $table->string('days');
            $table->string('from_time');
            $table->string('to_time');
            $table->string('categories');
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_schedules');
    }
};
