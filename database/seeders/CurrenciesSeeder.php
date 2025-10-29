<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'rate' => 1.00],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'rate' => 1.10],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'rate' => 1.25],
            ['name' => 'Nigerian Naira', 'code' => 'NGN', 'symbol' => '₦', 'rate' => 1600.00],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
