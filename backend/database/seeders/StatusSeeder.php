<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        // This adds the 3 columns to the statuses table
        DB::table('statuses')->insert([
            ['id' => 1, 'name' => 'To do'],
            ['id' => 2, 'name' => 'In behandeling'],
            ['id' => 3, 'name' => 'Voltooid'],
        ]);
    }
}
