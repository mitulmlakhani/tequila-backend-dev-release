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
        Schema::create('sync_changes', function (Blueprint $table) {                  
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))->unique();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->string('table_name', 100);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->uuid('record_uuid')->nullable();
            $table->enum('operation', ['insert', 'update', 'delete']);
            $table->json('changed_fields')->nullable();
            $table->unsignedBigInteger('sync_version');
            $table->boolean('is_processed')->default(false);
            $table->timestamp('created_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_changes');
    }
};
