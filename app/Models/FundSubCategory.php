<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FundCategory;
use App\Models\Fund;

class FundSubCategory extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(FundCategory::class,"category_id");
    }

    public function funds()
    {
        return $this->hasMany(Fund::class);
    }
}
