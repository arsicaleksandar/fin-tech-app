<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Fund;

class UserFund extends Model
{
    use HasFactory;


    protected $fillable=["user_id","fund_id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class, "fund_id");
    }

    public static function getUserFundIds($userId)
    {
        return UserFund::where('user_id', $userId)->select('fund_id');
    }
}
