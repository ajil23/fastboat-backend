<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedulesschedule')->insert([
            'sch_company' => 48,
            'sch_name' => 'Aman Dia FB 1',
            'sch_updated_by' => 1,
        ]);
        DB::table('schedulesschedule')->insert([
            'sch_company' => 48,
            'sch_name' => 'Aman Dia FB 2',
            'sch_updated_by' => 1,
        ]);
        DB::table('schedulesschedule')->insert([
            'sch_company' => 8,
            'sch_name' => 'Eka Jaya FB 1',
            'sch_updated_by' => 1,
        ]);
        DB::table('schedulesschedule')->insert([
            'sch_company' => 8,
            'sch_name' => 'Eka Jaya FB 2',
            'sch_updated_by' => 1,
        ]);
    }
}
