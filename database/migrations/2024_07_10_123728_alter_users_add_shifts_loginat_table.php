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
        Schema::table('users', function (Blueprint $table) {
             // Add shift_id column
             $table->unsignedBigInteger('shift_id')->nullable();
             // Add last_login_at column
             $table->dateTime('last_login_at')->nullable();
             // Add foreign key constraint for shift_id
             $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint for shift_id
            $table->dropForeign(['shift_id']);
            // Drop the shift_id column
            $table->dropColumn('shift_id');
            // Drop the last_login_at column
            $table->dropColumn('last_login_at');
        });
    }
};
