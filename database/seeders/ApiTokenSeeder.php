<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApiToken;
use Illuminate\Support\Str;

class ApiTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $token = Str::random(60); // Menghasilkan token acak
        ApiToken::create(['token' => $token]); // Menyimpan token ke database
    }
}
