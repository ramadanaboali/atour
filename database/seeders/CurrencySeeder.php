<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'name' => 'United States Dollar',
                'encyclopedia' => 'The official currency of the United States of America, also widely used as a global reserve currency.',
                'rate_to_sar' => 3.75, // 1 USD ≈ 3.75 SAR
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'encyclopedia' => 'The official currency of the Eurozone, used by 20 of the 27 European Union countries.',
                'rate_to_sar' => 4.10, // Approximate
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound Sterling',
                'encyclopedia' => 'The currency of the United Kingdom, known as the oldest currency still in use.',
                'rate_to_sar' => 4.85, // Approximate
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'encyclopedia' => 'The official currency of Japan, one of the most traded currencies in the world.',
                'rate_to_sar' => 0.025, // 1 JPY ≈ 0.025 SAR
            ],
            [
                'code' => 'SAR',
                'name' => 'Saudi Riyal',
                'encyclopedia' => 'The official currency of Saudi Arabia, pegged to the US Dollar.',
                'rate_to_sar' => 1.00,
            ],
            [
                'code' => 'AED',
                'name' => 'United Arab Emirates Dirham',
                'encyclopedia' => 'The official currency of the UAE, introduced in 1973 to replace the Qatari riyal.',
                'rate_to_sar' => 1.02, // 1 AED ≈ 1.02 SAR
            ],
            [
                'code' => 'EGP',
                'name' => 'Egyptian Pound',
                'encyclopedia' => 'The official currency of Egypt, first introduced in 1834.',
                'rate_to_sar' => 0.12, // Approximate
            ],
            [
                'code' => 'INR',
                'name' => 'Indian Rupee',
                'encyclopedia' => 'The official currency of India, symbolized by ₹ and regulated by the Reserve Bank of India.',
                'rate_to_sar' => 0.045, // 1 INR ≈ 0.045 SAR
            ],
            [
                'code' => 'CNY',
                'name' => 'Chinese Yuan',
                'encyclopedia' => 'The official currency of the People’s Republic of China, also called Renminbi (RMB).',
                'rate_to_sar' => 0.52, // 1 CNY ≈ 0.52 SAR
            ],
            [
                'code' => 'CHF',
                'name' => 'Swiss Franc',
                'encyclopedia' => 'The currency of Switzerland and Liechtenstein, known for its stability.',
                'rate_to_sar' => 4.25, // Approximate
            ],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
