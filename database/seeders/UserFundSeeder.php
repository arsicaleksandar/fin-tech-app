<?php

namespace Database\Seeders;

use App\Models\UserFund;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $userFunds = [];

        $primaryId = 1;

        for ($i = 1; $i <= 10; $i++) {
            for ($j = 1; $j <= 10; $j++) {
                $item = [
                    'id' => $primaryId++,
                    'user_id' => $i,
                    'fund_id' => rand(1, 10000)
                ];
                $userFunds[] = $item;
            }
        }

        foreach ($userFunds as $fund) {
            UserFund::create($fund);
        }
    }
}
