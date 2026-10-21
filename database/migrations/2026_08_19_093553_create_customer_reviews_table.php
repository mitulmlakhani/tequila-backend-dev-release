<?php

use App\Models\ReservationCustomer;
use App\Models\Restaurant;
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
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->default(DB::raw('(UUID())'))->unique();
            $table->foreignIdFor(Restaurant::class);
            $table->foreignIdFor(ReservationCustomer::class)->nullable();
            $table->tinyInteger('rating');
            $table->tinyText('review_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_reviews');
    }
};
