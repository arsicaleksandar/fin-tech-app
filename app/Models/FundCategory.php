<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FundSubCategory;
use App\Models\Fund;

class FundCategory extends Model
{
    use HasFactory;

    public function subcategories()
    {
        return $this->hasMany(FundSubCategory::class);
    }

    public function funds()
    {
        return $this->hasMany(Fund::class);
    }
}
