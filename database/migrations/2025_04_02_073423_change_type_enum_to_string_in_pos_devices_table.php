<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->string('type')->nullable()->change(); // Convert enum to string
        });
    }

    public function down(): void
    {
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->enum('type', ['kds', 'pos', 'printer'])->nullable()->change(); // rollback enum
        });
    }
};
