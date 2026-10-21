<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = DB::select('SHOW TABLES');

        $database = DB::getDatabaseName();
        $key = "Tables_in_$database";

        DB::statement('SET SQL_LOG_BIN = 0;');

        foreach ($tables as $table) {

            $tableName = $table->$key;

            if ($this->shouldSkip($tableName)) {
                continue;
            }

            if (!Schema::hasColumn($tableName, 'uuid')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->uuid('uuid')
                        ->nullable()
                        ->default(DB::raw('(UUID())'))
                        ->after('id')
                        ->unique();
                });
            }
        }

        DB::statement('SET SQL_LOG_BIN = 1;');

        $this->populateExistingUuids();
    }

    public function down(): void
    {
        $tables = DB::select('SHOW TABLES');

        $database = DB::getDatabaseName();
        $key = "Tables_in_$database";

        foreach ($tables as $table) {

            $tableName = $table->$key;

            if (Schema::hasColumn($tableName, 'uuid')) {

                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('uuid');
                });
            }
        }
    }

    protected function shouldSkip($table)
    {
        return in_array($table, [
            'migrations',
            'failed_jobs',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'password_reset_tokens',
            'personal_access_tokens',
        ]);
    }

    protected function populateExistingUuids()
    {
        $tables = DB::select('SHOW TABLES');

        $database = DB::getDatabaseName();
        $key = "Tables_in_$database";

        foreach ($tables as $table) {

            $tableName = $table->$key;

            if ($this->shouldSkip($tableName)) {
                continue;
            }

            if (Schema::hasColumn($tableName, 'uuid')) {

                // Fetch only IDs of rows missing a UUID
                $rows = DB::table($tableName)
                    ->whereNull('uuid')
                    ->pluck('id');

                foreach ($rows as $id) {
                    DB::table($tableName)
                        ->whereNull('uuid')
                        ->orderBy('id')
                        ->chunk(500, function ($rows) use ($tableName) {
                            foreach ($rows as $row) {
                                DB::table($tableName)
                                    ->where('id', $row->id)
                                    ->update(['uuid' => \Illuminate\Support\Str::uuid()]);
                            }
                        });
                }
            }
        }
    }
};
