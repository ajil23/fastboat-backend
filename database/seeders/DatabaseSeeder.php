<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RouteSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(IslandSeeder::class);
        $this->call(PortSeeder::class);
        $this->call(FastBoatSeeder::class);
        $this->call(ScheduleSeeder::class);
        $this->call(ShuttleAreaSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(NationalitySeeder::class);
        $this->call(PaymentMethodSeeder::class);
    }
}
