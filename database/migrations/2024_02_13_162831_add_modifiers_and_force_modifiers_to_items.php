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
        Schema::table('items', function (Blueprint $table) {
            $table->after('is_variant', function ($table) {
                $table->string('price');
                $table->foreignId('variant_group_id')->nullable()->references('id')->on('variants_group')->onDelete('cascade');
                $table->foreignId('modifier_group_id')->nullable()->references('id')->on('modifier_groups')->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
