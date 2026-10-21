<?php

use App\Models\Station;
use App\Models\Table;
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
        Schema::create('station_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Station::class)->constrained();
            $table->foreignIdFor(Table::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_tables');
    }
};
