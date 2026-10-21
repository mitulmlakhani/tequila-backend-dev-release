<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->bigInteger('local_id')->nullable()->after('id')->index();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::table('terminal_settings', function (Blueprint $table) {
            $table->dropColumn(['local_id', 'deleted_at']);
        });
    }
};
