<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'paypal',
            'py_name' => 'Paypal',
            'py_updated_by' => 1,
        ]);
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'midtrans',
            'py_name' => 'Midtrans',
            'py_updated_by' => 1,
        ]);
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'bank_transfer',
            'py_name' => 'Bank Transfer',
            'py_updated_by' => 1,
        ]);
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'pak_anang',
            'py_name' => 'Pak Anang',
            'py_updated_by' => 1,
        ]);
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'pay_on_port',
            'py_name' => 'Pay on Port (Collect)',
            'py_updated_by' => 1,
        ]);
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'cash',
            'py_name' => 'Cash',
            'py_updated_by' => 1,
        ]);
        DB::table('masterpaymentmethod')->insert([
            'py_value' => 'agent',
            'py_name' => 'Agent',
            'py_updated_by' => 1,
        ]);
    }
}
