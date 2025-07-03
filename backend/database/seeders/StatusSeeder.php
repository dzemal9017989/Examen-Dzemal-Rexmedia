<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('statussen')->insert([
            ['id' => 1, 'naam' => 'To do'],
            ['id' => 2, 'naam' => 'In behandeling'],
            ['id' => 3, 'naam' => 'Voltooid'],
        ]);
    }
}
