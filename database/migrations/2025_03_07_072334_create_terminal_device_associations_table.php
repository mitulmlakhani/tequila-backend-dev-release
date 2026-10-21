<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalDeviceAssociationsTable extends Migration
{
    public function up()
    {
        Schema::create('terminal_device_associations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terminal_id');
            $table->unsignedBigInteger('device_id');
            $table->boolean('status')->default(0); // 0 = Vacant, 1 = Paired
            $table->timestamps();

            $table->foreign('terminal_id')->references('id')->on('terminal_settings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('terminal_device_associations');
    }
}
