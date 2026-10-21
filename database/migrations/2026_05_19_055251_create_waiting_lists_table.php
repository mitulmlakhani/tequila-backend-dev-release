<?php

use App\Models\ReservationCustomer;
use App\Models\Restaurant;
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
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class)->constrained();
            $table->foreignIdFor(ReservationCustomer::class)->constrained();
            $table->foreignIdFor(Table::class)->nullable()->constrained();
            $table->integer('waiting_time')->nullable();
            $table->integer('guest_size')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyText('notes')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_lists');
    }
};
