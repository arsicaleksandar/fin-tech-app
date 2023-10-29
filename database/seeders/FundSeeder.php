<?php

namespace Database\Seeders;

use App\Models\Fund;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funds = [];

        for ($i = 1; $i <= 10000; $i++) {

            $item = [
                'id' => $i,
                'fund_category_id' => rand(1, 10),
                'fund_sub_category_id' => rand(1, 100),
                'name' => 'Fund' . $i,
                'wkn' => 'WKN' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'isin' => 'ISIN' . str_pad($i, 5, '0', STR_PAD_LEFT)
            ];

            $funds[] = $item;
        }

        foreach ($funds as $fund) {
            Fund::create($fund);
        }
    }
}
