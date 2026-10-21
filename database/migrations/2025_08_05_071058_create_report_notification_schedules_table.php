<?php

use App\Models\Restaurant;
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
        Schema::create('report_notification_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class)->constrained();
            $table->string('report');
            $table->string('emails');
            $table->string('schedule');
            $table->string('format');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_notification_schedules');
    }
};
