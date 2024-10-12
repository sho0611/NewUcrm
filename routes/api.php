<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AnalysisMouthController;
use App\Http\Controllers\AnalysisYearController;
use App\Http\Controllers\AnalysisDesileController;
use App\Http\Controllers\AnalysisRfmController;
use App\Http\Controllers\getArrayController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// //モーダルウィンドウで顧客検索用
// Route::middleware('auth:sanctum')->get('/
// searchCustomers', function (Request $request){
// return Customer::searchCustomers($request->search)
// ->select('id', 'name', 'kana', 'tel')->paginate(50);
// });


Route::get('analysis', [AnalysisController::class, 'index']);
Route::get('analysisMouth', [AnalysisMouthController::class, 'index']);
Route::get('analysisYear', [AnalysisYearController::class, 'index']);
Route::get('analysisDesile', [AnalysisDesileController::class, 'index']);
Route::get('analysisRfm', [AnalysisRfmController::class, 'index']);
Route::get('getArray', [getArrayController::class, 'index']);

Route::prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/view', 'viewItems')->name('viewItems');  // GET /api/items/view       
    Route::get('/viewItemCustomers', 'viewItemCustomers')->name('viewItemCustomers');// GET /api/items/viewItemCustomers
    Route::get('create', 'create')->name('create');   // GET /api/items/create
    Route::post('/', 'store')->name('store');        // POST /api/items
    Route::get('{item}', 'show')->name('show');       // GET /api/items/{item}
    Route::get('{item}/edit', 'edit')->name('edit');  // GET /api/items/{item}/edit
    Route::put('{item}', 'update')->name('update');   // PUT /api/items/{item}
    Route::delete('{item}', 'destroy')->name('destroy'); // DELETE /api/items/{item}

});

Route::prefix('customers')->controller(CustomerController::class)->group(function () {
    Route::get('/view', 'viewCustomers')->name('index'); // GET /api/customers
    Route::get('/viewCustomerItems', 'viewCustomerItems')->name('viewCustomerItems'); // GET /api/viewCustomerItems
    Route::get('create', 'create')->name('create'); // GET /api/customers/create
    Route::post('/', 'store')->name('store'); // POST /api/customers
    Route::get('{customer}', 'show')->name('show'); // GET /api/customers/{customer}
    Route::get('{customer}/edit', 'edit')->name('edit');// GET /api/customers/{customer}/edit
    Route::put('{customer}', 'update')->name('update'); //PUT /api/customers/{customer}
    Route::delete('{customer}', 'destroy')->name('destroy');// DELETE /api/customers/{customer}
});

Route::prefix('purchases')->controller(PurchaseController::class)->group(function () {
    Route::get('/', 'index')->name('index'); // GET /api/purchases
    Route::get('create', 'create')->name('create'); // GET /api/purchases/create
    Route::post('/', 'store')->name('store'); // POST /api/purchases
    Route::get('{purchase}', 'show')->name('show'); // GET /api/purchases/{purchase}
    Route::get('{purchase}/edit', 'edit')->name('edit'); // GET /api/purchases/{purchase}/edit
    Route::put('{purchase}', 'update')->name('update'); // PUT /api/purchases/{purchase}
    Route::delete('{purchase}', 'destroy')->name('destroy'); // DELETE /api/purchases/{purchase}
});














