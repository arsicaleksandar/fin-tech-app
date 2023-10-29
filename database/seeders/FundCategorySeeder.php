<?php

namespace Database\Seeders;

use App\Models\FundCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funds = [];

        for ($i = 1; $i <= 10; $i++) {
            $item = [
                'id' => $i,
                'name' => 'Category' . $i
            ];
            $funds[] = $item;
        }

        foreach($funds as $fund){
            FundCategory::create($fund);
        }
    }
}
