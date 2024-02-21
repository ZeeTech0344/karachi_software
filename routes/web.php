<?php

use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
Route::get('/', [HomeController::class, 'index'])->name("home_page");

Route::get('/home', [HomeController::class, 'home'])->name("home");

Route::get('/get-supplier-list', [HomeController::class, 'getSupplierList']);

Route::post('/delete-supplier-record', [HomeController::class, 'deleteSupplierRecord']);

Route::post('/insert-buyer-purchaser-record', [HomeController::class, 'insertBuyerPurchaserRecord']);

Route::get('/buyer-purchaser-record-list', [HomeController::class, 'insertBuyerPurchaserRecordList']);

Route::post('/edit-buyer-purchaser-detail', [HomeController::class, 'buyerPurchaserRecordStatusUpdate']);

Route::post('/update-status-buyer-purchaser-detail', [HomeController::class, 'updateStatusBuyerPurchaserDetail']);


// one form of transaction
Route::get('/transaction-form/{type?}/{id?}', [HomeController::class, 'transactionForm']);

Route::get('/buyer-purchaser-list', [HomeController::class, 'buyerPurchaserList']);

Route::post('/update-supplier-status', [HomeController::class, 'updateSupplierStatus']);

//second form of transaction
Route::get('/transaction-form-new', [HomeController::class, 'transactionFormNew']);

Route::post('/insert-supplier-data', [HomeController::class, 'insertSupplierData']);

Route::post('/supplier-amount-recieved', [HomeController::class, 'supplierAmountRecieved']);

Route::post('/insert-expense', [HomeController::class, 'insertExpense']);

Route::get('/select-supplier-for-ledger', [HomeController::class, 'selectSupplierForLedger']);

Route::get('/get-supplier-record/{from_date}/{to_date}/{supplier_id}/{supplier_name}', [HomeController::class, 'getSupplierRecord']);

Route::get('/get-supplier-record-from-header/{duration}/{supplier_id}/{supplier_name}', [HomeController::class, 'getSupplierRecordFromHeader']);


Route::get('/get-expense-ledger/{from_date}/{to_date}', [HomeController::class, 'getExpenseLedger']);
Route::get('/get-expense-ledger-from-header/{duration}/{expense_name?}', [HomeController::class, 'getExpenseFromHeader']);


Route::get('/supplier-info-view/{id}', [HomeController::class, 'supplierInfoView']);

Route::post('/update-supplier-data', [HomeController::class, 'updateSupplierData']);


Route::post('/delete-supplier-data', [HomeController::class, 'deleteSupplierData']);

Route::get('/supplier-data-pdf', [HomeController::class, 'supplierDataPdf']);

Route::get('/logout', [\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'destroy']);

// Route::get('/register', [\Laravel\Fortify\Http\Controllers\RegisteredUserController::class, 'create']);

// cousting

Route::get('/create-items-form', [HomeController::class, 'createItems']);

Route::post('/insert-items', [HomeController::class, 'insertItems']);

Route::get('/item-rate-form', [HomeController::class, 'itemRateForm']);

Route::post('/insert-item-rate', [HomeController::class, 'insertItemRate']);

Route::get('/item-rate-list-view', [HomeController::class, 'itemRateListView']);

Route::get('/item-rate-list', [HomeController::class, 'itemRateList']);

Route::get('/get-item-rate', [HomeController::class, 'getItemRate']);

Route::post('/insert-item-data', [HomeController::class, 'insertItemData']);

Route::get('/get-cousting-report', [HomeController::class, 'getCoustingReport']);

Route::get('/get-cousting-report-view', [HomeController::class, 'getCoustingReportView']);

Route::get('/edit-invoice-or-item/{invoice_no}', [HomeController::class, 'editInvoiceOrItem']);

Route::post('/delete-item-data', [HomeController::class, 'deleteItemData']);

Route::get('/rate-list-filter', [HomeController::class, 'rateListFilter']);

Route::get('/rate-list-filter-view/{item_id}/{from_date}/{to_date}', [HomeController::class, 'rateListFilterView']);

});
