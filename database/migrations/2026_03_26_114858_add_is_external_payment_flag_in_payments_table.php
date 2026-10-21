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
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_external')->default(0);
            
            DB::statement("
                ALTER TABLE payments 
                MODIFY source ENUM('local','ubereats','doordash','grubhub','external_pax') DEFAULT 'local'
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('is_external');

            DB::statement("
                ALTER TABLE payments 
                MODIFY source ENUM('local','ubereats','doordash','grubhub') DEFAULT 'local'
            ");
        });
    }
};
