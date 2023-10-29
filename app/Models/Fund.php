<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FundCategory;
use App\Models\FundSubCategory;
use App\Models\UserFund;

class Fund extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(FundCategory::class, "fund_category_id");
    }

    public function subCategory()
    {
        return $this->belongsTo(FundSubCategory::class, "fund_sub_category_id");
    }

    public function users()
    {
        return $this->hasMany(UserFund::class);
    }


    public static function getFilters($search, $filter, $filterSubCategory, $userId = null, $filterByUser = true)
    {
        $query = Fund::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('isin', 'like', "%$search%")
                    ->orWhere('wkn', 'like', "%$search%");
            });
        }

        if (isset($filter) && !isset($search)) {
            $query->where(function ($query) use ($filter) {
                $query->whereHas('category', function ($query) use ($filter) {
                    $query->where('id', '=', $filter);
                });
            });
        }

        if (isset($filterSubCategory) && !isset($search)) {
            $query->where(function ($query) use ($filterSubCategory) {
                $query->whereHas('subCategory', function ($query) use ($filterSubCategory) {
                    $query->where('id', '=', $filterSubCategory);
                });
            });
        }

        if (isset($userId)) {
            if ($filterByUser) {
                $query->where(function ($query) use ($userId) {
                    $query->whereHas('users', function ($query) use ($userId) {
                        $query->where('user_id', '=', $userId);
                    });
                });
            } else {
                $query->whereDoesntHave('users', function ($query) use ($userId) {
                    $query->where('user_id', '=', $userId);
                });
            }
        }

        return $query;
    }
}
