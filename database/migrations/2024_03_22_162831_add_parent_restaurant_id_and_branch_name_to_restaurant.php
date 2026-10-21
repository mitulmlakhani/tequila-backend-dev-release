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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->after('is_cc_fee_show', function ($table) {
                $table->boolean('is_multi_branch')->default(false);
                $table->string('max_branch_limit')->nullable();
                $table->foreignId('parent_id')->nullable()->references('id')->on('restaurants')->onDelete('cascade');
                $table->string('branch_name')->nullable();
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
