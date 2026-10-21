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
        Schema::create('tip_pool_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\TipSetting::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Restaurant::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\TipPool::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->enum('tip_type', ['tip_share', 'tip_pool'])->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('amount_type', ['fixed', 'percentage'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tip_pool_employees');
    }
};
