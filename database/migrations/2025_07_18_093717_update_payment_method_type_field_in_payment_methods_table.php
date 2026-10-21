<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $newValues = array_merge(getEnumFieldValues('payment_methods', 'type'), ['delivery_partner']);

        Schema::table('payment_methods', function (Blueprint $table) use ($newValues) {
            $table->enum('type', $newValues)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $newValues = array_diff(getEnumFieldValues('payment_methods', 'type'), ['delivery_partner']);

        Schema::table('payment_methods', function (Blueprint $table) use ($newValues) {
            $table->enum('type', $newValues)->change();
        });
    }
};
