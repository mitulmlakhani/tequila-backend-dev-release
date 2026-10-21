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
        Schema::create('vendor_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\VendorInvoice::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Ingredient::class)->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_invoice_items');
    }
};
