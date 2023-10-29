<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserFundRequest;
use App\Http\Requests\UpdateUserFundRequest;
use App\Http\Resources\UserFundCollection;
use App\Models\Fund;
use App\Models\FundCategory;
use App\Models\FundSubCategory;
use App\Models\UserFund;
use Illuminate\Support\Facades\Auth;

class UserFundController extends MyBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        $user = Auth::user();

        $fund = Fund::find($id);

        if (!empty($fund)) {
            UserFund::create(["user_id" => $user->id, "fund_id" => $id]);
        }

        return redirect()->route('user.fund.create')->with('success', 'Fund added from favorites');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFund $userFund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserFund $userFund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserFundRequest $request, UserFund $userFund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $fund = Fund::find($id);

        if (!empty($fund)) {
            UserFund::where('user_id', $user->id)
                ->where('fund_id', $fund->id)
                ->delete();

            return redirect()->route('user.fund.index')->with('success', 'Fund removed from favorites');
        }
    }
}
