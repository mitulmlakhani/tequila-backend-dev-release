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
        Schema::create('tip_pools', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\TipSetting::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Restaurant::class)->constrained()->onDelete('cascade');
            $table->string('pool_name');
            $table->decimal('tip_percentage', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tip_pools');
    }
};
