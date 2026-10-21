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
        Schema::create('kitchen_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pos_devices', function (Blueprint $table) {
            // Ensure 'section' column is nullable to allow setting it to null on deletion
            $table->unsignedBigInteger('section')->nullable()->change();

            // Add foreign key constraint
            $table->foreign('section')
                ->references('id')
                ->on('kitchen_sections')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('pos_devices', function (Blueprint $table) {
        //     // Drop the foreign key first
        //     $table->dropForeign(['section']);
        // });
        
        Schema::dropIfExists('kitchen_sections');
    }
};
