<?php

use App\Http\Controllers\FundCategoryController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserFundController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FundController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/fundCategories', [FundCategoryController::class, 'index'])->name('fund.categories.index');

    Route::get('/user/funds', [FundController::class, 'userFavoriteFunds'])->name('user.fund.index');
    Route::get('/user/funds/create', [FundController::class, 'userNotFavoriteFunds'])->name('user.fund.create');
    Route::get('/user/funds/add/{id}', [UserFundController::class, 'store'])->name('user.fund.add');
    Route::get('/user/funds/destroy/{id}', [UserFundController::class, 'destroy'])->name('user.fund.destroy');


    Route::post('/fund-export-pdf', [FundController::class, 'fundExportPdf'])->name('fund.export.pdf');
    Route::post('/fund-export-excel', [FundController::class, 'fundExportExcel'])->name('fund.export.excel');
    Route::post('/fund-export-xsd', [FundController::class, 'fundExportXsd'])->name('fund.export.xsd');

    Route::post('/fund-export-all', [FundController::class, 'exportAll'])->name('fund.export.all');
});

Route::resource('/funds', FundController::class);

Route::get('/search', [FundController::class, 'search'])->name('fund.search');

require __DIR__ . '/auth.php';
