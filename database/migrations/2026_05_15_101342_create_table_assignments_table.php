<?php

use App\Models\PosDevice;
use App\Models\Table;
use App\Models\User;
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
        Schema::create('table_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Table::class)->nullable()->constrained();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->foreignIdFor(PosDevice::class)->nullable()->constrained();
            $table->enum('assign_type', ['pos', 'user']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_assignments');
    }
};
