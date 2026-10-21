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
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `users` DROP INDEX `users_passcode_unique`');
        } catch (\Throwable $e) {}

        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `users` ADD UNIQUE `users_restaurant_passcode_unique` (`restaurant_id`, `passcode`)');
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the new composite unique index
            $table->dropUnique('users_restaurant_passcode_unique');

            // Re-add the old unique constraint on passcode
            $table->unique('passcode', 'users_passcode_unique');
        });
    }
};
