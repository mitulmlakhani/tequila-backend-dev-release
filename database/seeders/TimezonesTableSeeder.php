<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezonesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('timezones')->updateOrInsert(
            ['name' => 'America/Chicago'],
            [
                'title' => 'CST',
                'offset' => '-06:00',
                'abbreviation' => 'CST',
            ]
        );
    }
}