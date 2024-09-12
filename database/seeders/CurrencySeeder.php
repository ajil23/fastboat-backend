<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mastercurrency')->insert([
            'cy_code' => 'AUD',
            'cy_name' => 'Australian Dollar',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'BRL',
            'cy_name' => 'Brazilian Real',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'CAD',
            'cy_name' => 'Canadian Dollar',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'CHF',
            'cy_name' => 'Swiss Franc',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'DKK',
            'cy_name' => 'Danish Krone',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'EUR',
            'cy_name' => 'Euro',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'GBP',
            'cy_name' => 'Pound Sterling',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'HKD',
            'cy_name' => 'Hongkong Dollar',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'HUF',
            'cy_name' => 'Hungarian Forint',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'IDR',
            'cy_name' => 'Indonesia Rupiah',
            'cy_updated_by' => 'Seeder',
        ]); 
        DB::table('mastercurrency')->insert([
            'cy_code' => 'ILS',
            'cy_name' => 'Israeli Shekel',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'JPY',
            'cy_name' => 'Japanese Yen',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'MXN',
            'cy_name' => 'Mexican Peso',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'MYR',
            'cy_name' => 'Malaysia Ringgit',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'NZD',
            'cy_name' => 'New Zealand Dollar',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'PHP',
            'cy_name' => 'Philippine Peso',
            'cy_updated_by' => 'Seeder',
        ]);DB::table('mastercurrency')->insert([
            'cy_code' => 'PLN',
            'cy_name' => 'Polish Zloty',
            'cy_updated_by' => 'Seeder',
        ]);DB::table('mastercurrency')->insert([
            'cy_code' => 'RUB',
            'cy_name' => 'Russian Ruble',
            'cy_updated_by' => 'Seeder',
        ]);DB::table('mastercurrency')->insert([
            'cy_code' => 'SEK',
            'cy_name' => 'Swedish Krona',
            'cy_updated_by' => 'Seeder',
        ]);DB::table('mastercurrency')->insert([
            'cy_code' => 'SGD',
            'cy_name' => 'Singapore Dollar',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'THB',
            'cy_name' => 'Thai Baht',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'TWD',
            'cy_name' => 'New Taiwan Dollar',
            'cy_updated_by' => 'Seeder',
        ]);
        DB::table('mastercurrency')->insert([
            'cy_code' => 'USD',
            'cy_name' => 'United States Dollar',
            'cy_updated_by' => 'Seeder',
        ]);
    }
}
