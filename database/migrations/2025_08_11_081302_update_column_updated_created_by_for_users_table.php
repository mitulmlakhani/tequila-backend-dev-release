<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Drop FKs on created_by / updated_by (if they exist) to remove insertion order dependency
        //    Names from your schema: users_created_by_foreign, users_updated_by_foreign.
        try { DB::statement('ALTER TABLE `users` DROP FOREIGN KEY `users_created_by_foreign`'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `users` DROP FOREIGN KEY `users_updated_by_foreign`'); } catch (\Throwable $e) {}

        // 2) Ensure passcode is truly nullable and normalize empty strings to NULL
        //    (MySQL UNIQUE allows multiple NULLs, but not multiple empty strings.)
        DB::table('users')->where('passcode', '')->update(['passcode' => DB::raw('NULL')]);
        try { DB::statement('ALTER TABLE `users` MODIFY `passcode` varchar(30) NULL'); } catch (\Throwable $e) {}

        // 3) Recreate the composite unique (restaurant_id, passcode) if missing or broken.
        //    First, drop if it exists with that name; then re-add.
        try { DB::statement('ALTER TABLE `users` DROP INDEX `users_restaurant_passcode_unique`'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `users` ADD UNIQUE `users_restaurant_passcode_unique` (`restaurant_id`, `passcode`)'); } catch (\Throwable $e) {}

        // 4) Add local_id (if not present) and a unique key to support updateOrCreate([restaurant_id, local_id])
        if (!Schema::hasColumn('users', 'local_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('local_id')->nullable()->after('id');
            });
        }

        // Create a stable unique index for (restaurant_id, local_id)
        // Drop if exists (best-effort), then add.
        try { DB::statement('ALTER TABLE `users` DROP INDEX `users_restaurant_local_unique`'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `users` ADD UNIQUE `users_restaurant_local_unique` (`restaurant_id`, `local_id`)'); } catch (\Throwable $e) {}

        // 5) Add plain indexes on created_by / updated_by for lookups (non-FK, no insertion ordering issues)
        //    Use predictable names; ignore if duplicates.
        try { DB::statement('CREATE INDEX `users_created_by_idx` ON `users` (`created_by`)'); } catch (\Throwable $e) {}
        try { DB::statement('CREATE INDEX `users_updated_by_idx` ON `users` (`updated_by`)'); } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        // Best-effort rollback

        // Drop helper indexes
        try { DB::statement('DROP INDEX `users_created_by_idx` ON `users`'); } catch (\Throwable $e) {}
        try { DB::statement('DROP INDEX `users_updated_by_idx` ON `users`'); } catch (\Throwable $e) {}

        // Drop (restaurant_id, local_id) unique
        try { DB::statement('ALTER TABLE `users` DROP INDEX `users_restaurant_local_unique`'); } catch (\Throwable $e) {}

        // Optionally drop local_id column
        if (Schema::hasColumn('users', 'local_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('local_id');
            });
        }

        // Recreate the created_by / updated_by foreign keys with safe behavior (still not deferrable in MySQL)
        // Note: This reintroduces insertion-order constraints if you actually roll back.
        Schema::table('users', function (Blueprint $table) {
            // Make sure columns are nullable
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();

            // Re-add FKs (null on delete; cascade on update)
            $table->foreign('created_by', 'users_created_by_foreign')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('updated_by', 'users_updated_by_foreign')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });

        // Keep the composite unique as-is
        try { DB::statement('ALTER TABLE `users` DROP INDEX `users_restaurant_passcode_unique`'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `users` ADD UNIQUE `users_restaurant_passcode_unique` (`restaurant_id`, `passcode`)'); } catch (\Throwable $e) {}
    }
};
