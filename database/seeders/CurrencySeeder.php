<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
  
        $currencies = [
            ['code' => 'SAR', 'symbol' => '﷼', 'rate' => 1],
            ['code' => 'USD', 'symbol' => '$', 'rate' => 0.27],
            ['code' => 'EUR', 'symbol' => '€', 'rate' => 0.25],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
