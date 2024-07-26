<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Bali',
            'rt_arrival_island' => 'Lombok/Gili',
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Bali',
            'rt_arrival_island' => 'Penida'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Bali',
            'rt_arrival_island' => 'Lembongan'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Gili',
            'rt_arrival_island' => 'Lombok'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Gili',
            'rt_arrival_island' => 'Bali'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Penida',
            'rt_arrival_island' => 'Bali'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Penida',
            'rt_arrival_island' => 'Lembongan'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Penida',
            'rt_arrival_island' => 'Lombok/Gili'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lembongan',
            'rt_arrival_island' => 'Bali'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lembongan',
            'rt_arrival_island' => 'Penida'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lembongan',
            'rt_arrival_island' => 'Lombok/Gili'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lombok',
            'rt_arrival_island' => 'Gili'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lombok/Gili',
            'rt_arrival_island' => 'Penida'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lombok/Gili',
            'rt_arrival_island' => 'Bali'
        ]);
        DB::table('dataroute')->insert([
            'rt_dept_island' => 'Lombok/Gili',
            'rt_arrival_island' => 'Lembongan'
        ]);
    }
}
