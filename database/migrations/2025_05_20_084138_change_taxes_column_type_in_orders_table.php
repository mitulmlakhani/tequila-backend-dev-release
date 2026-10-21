<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTaxesColumnTypeInOrdersTable extends Migration
{
    public function up()
    {
        // Change column type to TEXT without affecting existing data
        DB::statement("ALTER TABLE `orders` MODIFY `taxes` TEXT NULL");
    }

    public function down()
    {
        // Revert back to VARCHAR(255) if needed
        DB::statement("ALTER TABLE `orders` MODIFY `taxes` VARCHAR(255) NULL");
    }
}
