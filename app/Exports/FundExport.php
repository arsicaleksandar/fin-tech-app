<?php

namespace App\Exports;

use App\Http\Resources\FundCollection;
use App\Models\Fund;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class FundExport implements FromView
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function view(): View
    {
        return view('fund-xlsx', [
            'funds' => Fund::find($this->id)
        ]);
    }
}
