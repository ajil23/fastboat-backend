<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShuttleAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedulesshuttlearea')->insert([
            'sa_island' => 1,
            'sa_name' => 'Denpasar',
            'sa_updated_by' => 1,
        ]);
        DB::table('schedulesshuttlearea')->insert([
            'sa_island' => 1,
            'sa_name' => 'Kuta',
            'sa_updated_by' => 1,
        ]);
        DB::table('schedulesshuttlearea')->insert([
            'sa_island' => 1,
            'sa_name' => 'Jimbaran',
            'sa_updated_by' => 1,
        ]);
    }
}
