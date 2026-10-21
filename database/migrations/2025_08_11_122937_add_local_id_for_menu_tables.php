<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Tables that need (restaurant_id, local_id) for upsert
        $models = [
            'item_tags',
            'menucategorys',
            'unit_of_measurements',
            'modifiers',
            'modifier_groups',
            'ingredients',
            'variants_group',
            'variants',
            'pos_devices',
            'items',
            'item_variants',
            'item_ingredients',
            'item_modifiers',
            'item_modifier_groups',
        ];

        foreach ($models as $table) {
            if (!Schema::hasColumn($table, 'local_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('local_id')->nullable()->after('id');
                });
            }
            // Unique for idempotent updateOrCreate
            try { DB::statement("ALTER TABLE `$table` DROP INDEX `{$table}_restaurant_local_unique`"); } catch (\Throwable $e) {}
            try { DB::statement("ALTER TABLE `$table` ADD UNIQUE `{$table}_restaurant_local_unique` (`restaurant_id`,`local_id`)"); } catch (\Throwable $e) {}
        }

        // Relax created_by / updated_by for insertion order (best-effort if columns exist)
        // $hasCreator = ['items','modifiers','modifier_groups','ingredients','menucategorys','variants_group','variants'];
        // foreach ($hasCreator as $table) {
        //     if (Schema::hasColumn($table, 'created_by')) {
        //         try { DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `{$table}_created_by_foreign`"); } catch (\Throwable $e) {}
        //         try { DB::statement("ALTER TABLE `$table` MODIFY `created_by` BIGINT UNSIGNED NULL"); } catch (\Throwable $e) {}
        //     }
        //     if (Schema::hasColumn($table, 'updated_by')) {
        //         try { DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `{$table}_updated_by_foreign`"); } catch (\Throwable $e) {}
        //         try { DB::statement("ALTER TABLE `$table` MODIFY `updated_by` BIGINT UNSIGNED NULL"); } catch (\Throwable $e) {}
        //     }
        // }
    }

    public function down(): void
    {
        // Best-effort rollback of uniques and columns
        $models = [
            'item_tags','menucategorys','unit_of_measurements','modifiers','modifier_groups','ingredients',
            'variants_group','variants','pos_devices','items','item_variants','item_ingredients','item_modifiers','item_modifier_groups',
        ];
        foreach ($models as $table) {
            try { DB::statement("ALTER TABLE `$table` DROP INDEX `{$table}_restaurant_local_unique`"); } catch (\Throwable $e) {}
            if (Schema::hasColumn($table, 'local_id')) {
                Schema::table($table, function (Blueprint $t) { $t->dropColumn('local_id'); });
            }
        }
    }
};
