<?php

namespace Database\Seeders;

use App\Models\FundSubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundSubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subFunds = [];

        for ($i = 1; $i <= 100; $i++) {
            $item = [
                'id' => $i,
                'category_id' => (floor(($i - 1) / 10)) + 1,
                'name' => 'SubCategory' . $i
            ];

            $subFunds[] = $item;
        }

        foreach ($subFunds as $fund) {
            FundSubCategory::create($fund);
        }
    }
}
