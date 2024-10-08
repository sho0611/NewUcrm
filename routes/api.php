<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;;
use App\Http\Controllers\PurchaseController;
use App\Models\Customer;
use App\Http\Controllers\AnalysisController;

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

//モーダルウィンドウで顧客検索用
Route::middleware('auth:sanctum')->get('/
searchCustomers', function (Request $request){
return Customer::searchCustomers($request->search)
->select('id', 'name', 'kana', 'tel')->paginate(50);
});

//データ分析
Route::middleware('auth:sanctum')->get('/analysis',
[AnalysisController::class, 'index'])
->name('api.analysis');

// Route::middleware('auth')->post('/api/items', [ItemController::class, 'store']);


Route::prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/', 'index')->name('index');          // GET /api/items
    Route::get('create', 'create')->name('create');   // GET /api/items/create
    Route::post('/', 'store')->name('store');        // POST /api/items
    Route::get('{item}', 'show')->name('show');       // GET /api/items/{item}
    Route::get('{item}/edit', 'edit')->name('edit');  // GET /api/items/{item}/edit
    Route::put('{item}', 'update')->name('update');   // PUT /api/items/{item}
    Route::delete('{item}', 'destroy')->name('destroy'); // DELETE /api/items/{item}

});

Route::prefix('customers')->controller(CustomerController::class)->group(function () {
    Route::get('/', 'index')->name('index'); // GET /api/customers
    Route::get('create', 'create')->name('create'); // GET /api/customers/create
    Route::post('/', 'store')->name('store'); // POST /api/customers
});

Route::prefix('purchases')->controller(PurchaseController::class)->group(function () {
    Route::get('/', 'index')->name('index'); // GET /api/purchases
    Route::get('create', 'create')->name('create'); // GET /api/purchases/create
    Route::post('/', 'store')->name('store'); // POST /api/purchases
  
});











