<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountType;

class AccountTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Savings', 'slug' => 'savings', 'description' => 'Personal savings account', 'min_balance' => 0],
            ['name' => 'Current', 'slug' => 'current', 'description' => 'Everyday business/current account', 'min_balance' => 0],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business account for companies', 'min_balance' => 0],
        ];

        foreach ($types as $t) {
            AccountType::updateOrCreate(['slug' => $t['slug']], $t);
        }
    }
}
