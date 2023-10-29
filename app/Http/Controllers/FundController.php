<?php

namespace App\Http\Controllers;

use App\Exports\FundExport;
use App\Exports\FundsExport;
use App\Http\Resources\FundCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FundSubCategory;
use App\Models\FundCategory;
use Illuminate\Http\Request;
use App\Models\UserFund;
use SimpleXMLElement;
use App\Models\Fund;
use DOMDocument;
use ZipArchive;

class FundController extends MyBaseController
{
    const PAGINATE_LIMIT = 10;

    public function index()
    {
        $user = Auth::user();

        if (empty($user)) {
            $this->data['funds'] = new FundCollection(Fund::paginate(self::PAGINATE_LIMIT));
        } else {
            $this->data['funds'] = new FundCollection(Fund::with(['category', 'subCategory'])->paginate(self::PAGINATE_LIMIT));
        }

        $this->data['categories'] = FundCategory::all();
        $this->data['subCategories'] = FundSubCategory::all();

        return view('welcome',  $this->data);
    }

    public function userNotFavoriteFunds(Request $request)
    {
        $user = Auth::user();
        $fundIds = UserFund::getUserFundIds($user->id)->get();

        $search = $request->search;
        $filter = $request->filter;
        $filterSubCategory = $request->filterSubCategory;

        if (!isset($search) && !isset($filter) && !isset($filterSubCategory)) {
            $this->data['funds'] = new FundCollection(Fund::with(['category', 'subCategory'])
                ->whereNotIn('id', $fundIds)
                ->paginate(self::PAGINATE_LIMIT));
        } else {
            $this->data['search'] = $search;
            $this->data['filter'] = $filter;
            $this->data['filterSubCategory'] = $filterSubCategory;

            $this->data['funds'] = Fund::getFilters($search, $filter, $filterSubCategory, $user->id, false)->paginate(self::PAGINATE_LIMIT);
        }

        $this->data['categories'] = FundCategory::all();
        $this->data['subCategories'] = FundSubCategory::all();


        return view('user/fundAdd', $this->data);
    }

    public function userFavoriteFunds(Request $request)
    {
        $user = Auth::user();
        $fundIds = UserFund::getUserFundIds($user->id)->get();

        $search = $request->search;
        $filter = $request->filter;
        $filterSubCategory = $request->filterSubCategory;

        if (!isset($search) && !isset($filter) && !isset($filterSubCategory)) {
            $this->data['funds'] = new FundCollection(Fund::with(['category', 'subCategory'])
                ->whereIn('id', $fundIds)
                ->paginate(self::PAGINATE_LIMIT));
        } else {
            $this->data['search'] = $search;
            $this->data['filter'] = $filter;
            $this->data['filterSubCategory'] = $filterSubCategory;

            $this->data['funds'] = Fund::getFilters($search, $filter, $filterSubCategory, $user->id)->paginate(self::PAGINATE_LIMIT);
        }

        $this->data['categories'] = FundCategory::all();
        $this->data['subCategories'] = FundSubCategory::all();

        return view('user/fund', $this->data);
    }


    public function fundExportPdf(Request $request)
    {
        $this->data['funds'] = Fund::find($request->data);

        $pdf = PDF::loadView('fund-pdf', $this->data)->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download("fund{$request->data[0]}.pdf");
    }

    public function fundExportExcel(Request $request)
    {
        return Excel::download(new FundExport($request->data), "fund{$request->data[0]}-excel.xlsx");
    }

    public function fundExportXsd(Request $request)
    {
        $fund = Fund::find($request->data)->first();
        $xml = new SimpleXMLElement('<Funds></Funds>');

        $fundElement = $xml->addChild('Fund');
        $fundElement->addChild('Name', $fund->name);
        $fundElement->addChild('ISIN', $fund->isin);
        $fundElement->addChild('WKN', $fund->wkn);
        $fundElement->addChild('Category', $fund->category->name);
        $fundElement->addChild('SubCategory', $fund->subCategory->name);

        $directory = storage_path('app/uploads');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $filePath = $directory . '/funds.xml';

        file_put_contents($filePath, $xml->asXML());

        $xmlFilePath = storage_path('app/uploads/funds.xml');
        $xsdFilePath = public_path('xsd/funds.xsd');

        $xml = new DOMDocument();
        $xml->load($xmlFilePath);

        $xsd = new DOMDocument();
        $xsd->load($xsdFilePath);

        if ($xml->schemaValidateSource($xsd->saveXML())) {
            return Response::download($filePath, 'funds.xml', ['Content-Type' => 'application/xml']);
        } else {
            return response()->json(['message' => 'XML is not valid according to the XSD'], 400);
        }
    }

    public function exportAll(Request $request)
    {
        $user = Auth::user();
        $userId = isset($request->userFunds) ? $user->id : null;

        $this->data['funds'] = $this->searchForExport($request->search, $request->filter, $request->filterSubCategory, $userId);

        Excel::store(new FundsExport($request->search, $request->filter, $request->filterSubCategory, $userId), 'uploads/funds.xlsx');

        $pdf = PDF::loadView('fund-pdf', $this->data)->setOptions(['defaultFont' => 'sans-serif']);
        $pdfContent = $pdf->output();
        Storage::put('uploads/funds.pdf', $pdfContent);


        $this->fundExportXsdCollection($request->search, $request->filter, $request->filterSubCategory, $userId);

        return $this->fundExportZipCollection();
    }

    public function fundExportZipCollection()
    {
        $zip_file = storage_path('app/uploads/download-data.zip');
        $zip = new ZipArchive();

        if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            $zip->addFromString('funds.xlsx', file_get_contents(storage_path('app/uploads/funds.xlsx')));
            $zip->addFromString('funds.pdf', file_get_contents(storage_path('app/uploads/funds.pdf')));
            $zip->addFromString('funds.xml', file_get_contents(storage_path('app/uploads/funds.xml')));

            $zip->close();
        }

        return response()->download($zip_file, 'download-data.zip');
    }

    public function fundExportXsdCollection($search, $filter, $filterSubCategory, $userId)
    {
        $funds = $this->searchForExport($search, $filter, $filterSubCategory, $userId);
        $xml = new SimpleXMLElement('<Funds></Funds>');

        foreach ($funds as $fund) {
            $fundElement = $xml->addChild('Fund');
            $fundElement->addChild('Name', $fund->name);
            $fundElement->addChild('ISIN', $fund->isin);
            $fundElement->addChild('WKN', $fund->wkn);
            $fundElement->addChild('Category', $fund->category->name);
            $fundElement->addChild('SubCategory', $fund->subCategory->name);
        }

        $directory = storage_path('app/uploads');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $filePath = $directory . '/funds.xml';

        file_put_contents($filePath, $xml->asXML());

        $xmlFilePath = storage_path('app/uploads/funds.xml');
        $xsdFilePath = public_path('xsd/funds.xsd');

        $xml = new DOMDocument();
        $xml->load($xmlFilePath);

        $xsd = new DOMDocument();
        $xsd->load($xsdFilePath);

        if ($xml->schemaValidateSource($xsd->saveXML())) {
            return Response::download($filePath, 'funds.xml', ['Content-Type' => 'application/xml']);
        } else {
            return response()->json(['message' => 'XML is not valid according to the XSD'], 400);
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $filterSubCategory = $request->filterSubCategory;

        if (!isset($search) && !isset($filter) && !isset($filterSubCategory)) {
            $this->data['funds'] = new FundCollection(Fund::with(['category', 'subCategory'])->paginate(self::PAGINATE_LIMIT));
        } else {
            $this->data['search'] = $search;
            $this->data['filter'] = $filter;
            $this->data['filterSubCategory'] = $filterSubCategory;

            $this->data['funds'] = Fund::getFilters($search, $filter, $filterSubCategory)->paginate(self::PAGINATE_LIMIT);
        }

        $this->data['categories'] = FundCategory::all();
        $this->data['subCategories'] = FundSubCategory::all();

        return view('welcome', $this->data);
    }

    public function searchForExport($search, $filter, $filterSubCategory, $userId = null)
    {
        if (!isset($search) && !isset($filter) && !isset($filterSubCategory) && !isset($userId)) {
            return new FundCollection(Fund::with(['category', 'subCategory'])->get());
        } else {
            return new FundCollection(Fund::getFilters($search, $filter, $filterSubCategory, $userId)->with('category', 'subCategory')->get());
        }
    }
}
