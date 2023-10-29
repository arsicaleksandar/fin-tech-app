<?php

namespace App\Exports;

use App\Http\Resources\FundCollection;
use App\Models\Fund;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class FundsExport implements FromView
{
    use Exportable;

    protected $search;
    protected $filter;
    protected $filterSubCategory;
    protected $userId;

    public function __construct($search, $filter, $filterSubCategory, $userId = null)
    {
        $this->search = $search;
        $this->filter = $filter;
        $this->filterSubCategory = $filterSubCategory;
        $this->userId = $userId;
    }

    public function view(): View
    {
        return view('fund-xlsx', [
            'funds' => new FundCollection(Fund::getFilters($this->search, $this->filter, $this->filterSubCategory, $this->userId)
                ->with('category', 'subCategory')->get())
        ]);
    }
}
