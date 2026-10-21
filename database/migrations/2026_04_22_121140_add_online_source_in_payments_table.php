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

            $table->string('online_payment_id')->nullable()->after('signature');
            $table->json('online_payment_details')->nullable()->after('online_payment_id');

            DB::statement("
                ALTER TABLE payments 
                MODIFY source ENUM('local','ubereats','doordash','grubhub','external_pax', 'online') DEFAULT 'local'
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropColumn('online_payment_id');
            $table->dropColumn('online_payment_details');

            DB::statement("
                ALTER TABLE payments 
                MODIFY source ENUM('local','ubereats','doordash','grubhub','external_pax') DEFAULT 'local'
            ");
        });

    }
};
