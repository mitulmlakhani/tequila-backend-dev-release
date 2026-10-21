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
        Schema::table('vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });

        Schema::table('vendor_invoice_items', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->dropColumn('local_id');
        }); 

        Schema::table('vendor_invoice_items', function (Blueprint $table) {
            $table->dropColumn('local_id');
        });
    }
};
