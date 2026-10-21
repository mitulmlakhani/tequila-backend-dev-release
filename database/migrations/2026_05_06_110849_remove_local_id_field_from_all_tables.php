<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        $tables = DB::select('SHOW TABLES');

        $database = DB::getDatabaseName();
        $key = "Tables_in_$database";

        DB::statement('SET SQL_LOG_BIN = 0;');
        DB::statement('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {

            $tableName = $table->$key;

            if (!Schema::hasColumn($tableName, 'local_id')) {
                continue;
            }

            // Find indexes using local_id
            $indexes = DB::select("
                SHOW INDEX FROM `$tableName`
                WHERE Column_name = 'local_id'
            ");

            $indexNames = collect($indexes)
                ->pluck('Key_name')
                ->unique();

            // Drop indexes first
            foreach ($indexNames as $indexName) {
                if ($indexName === 'PRIMARY') {
                    continue;
                }

                try {
                    DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$indexName}`");
                } catch (\Throwable $e) {}
            }

            // Then drop column
            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP COLUMN `local_id`");
            } catch (\Throwable $e) {}
        }

        Schema::dropIfExists('sync_id_mappings');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        DB::statement('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;');
        DB::statement('SET SQL_LOG_BIN = 1;');
    }

    public function down(): void
    {
    }
};