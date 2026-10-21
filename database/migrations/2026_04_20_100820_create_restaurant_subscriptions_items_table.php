<?php

use App\Models\PosFeature;
use App\Models\RestaurantSubscription;
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
        Schema::create('restaurant_subscriptions_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RestaurantSubscription::class)->constrained('restaurant_subscriptions', 'id', 'rs_items_rs_id_fk');
            $table->foreignIdFor(PosFeature::class)->constrained();
            $table->decimal('amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_subscriptions_items');
    }
};
