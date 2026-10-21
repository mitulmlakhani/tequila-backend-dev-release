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
        Schema::table('terminal_settings', function (Blueprint $table) {
            // Add a new 'name' field
            $table->string('name')->nullable()->after('restaurant_id');

            // Update the default value for the 'status' field
            $table->boolean('status')->default(0)->comment('0: Vacant, 1: Paired')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('terminal_settings', function (Blueprint $table) {
            // Remove the 'name' field
            $table->dropColumn('name');

            // Revert the 'status' field default value and remove the comment
            $table->boolean('status')->default(1)->change();
        });
    }
};
