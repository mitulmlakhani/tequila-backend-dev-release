<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('happy_hours_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->default(DB::raw('(UUID())'))->unique();
            $table->foreignIdFor(\App\Models\Restaurant::class);

            $table->string('name');
            $table->json('order_types')->nullable();
            $table->json('schedule')->nullable();
            $table->boolean('is_active')->default(0);

            $table->timestamps();
        });

        Schema::create('happy_hours_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->default(DB::raw('(UUID())'))->unique();
            $table->foreignIdFor(\App\Models\Restaurant::class);
            $table->foreignId('happy_hours_setting_id')->constrained('happy_hours_settings')->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Item::class);
            $table->foreignIdFor(\App\Models\ItemVariant::class)->nullable();
            $table->decimal('price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('happy_hours_items');
        Schema::dropIfExists('happy_hours_settings');
    }
};
