<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AnalysisDesileController;
use App\Http\Controllers\AnalysisRfmController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;

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

//売り上げ分析 日/月/年
Route::prefix('analysis')->controller(AnalysisController::class)->group(function () {
    Route::get('/day', 'analysisDay')->name('analysisDay');
    Route::get('/month', 'analysisMonth')->name('analysisMonth');
    Route::get('/year', 'analysisYear')->name('analysisYear');
});

//データ分析 デシル
Route::get('analysisDesile', [AnalysisDesileController::class, 'index']);

//データ分析 RFM
Route::get('analysisRfm', [AnalysisRfmController::class, 'index']);

//item関連
Route::prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/view', 'viewItems')->name('viewItems');  // GET /api/items/view       
    Route::get('/{item}/get', 'getItemDetail')->name('getItemDetail');// GET /api/items/view
    Route::get('create', 'create')->name('create');   // GET /api/items/create
    Route::get('{item}', 'show')->name('show');       // GET /api/items/{item}
    Route::put('{item}', 'update')->name('update');   // PUT /api/items/{item}
    Route::delete('{item}', 'destroy')->name('destroy'); // DELETE /api/items/{item}
});

//customer関連
Route::prefix('customers')->controller(CustomerController::class)->group(function () {
    Route::get('/view', 'viewCustomers')->name('index'); // GET /api/customers
    Route::get('/viewCustomerItems', 'viewCustomerItems')->name('viewCustomerItems'); // GET /api/viewCustomerItems
    Route::get('/{customer}/get', 'getCustomerDetail')->name('getCustomerDetail'); // GET /api/get
    Route::get('create', 'create')->name('create'); // GET /api/customers/create
    Route::get('{customer}', 'show')->name('show'); // GET /api/customers/{customer}
    Route::put('{customer}', 'update')->name('update'); //PUT /api/customers/{customer}
    Route::delete('{customer}', 'destroy')->name('destroy');// DELETE /api/customers/{customer}
    Route::get('/register', 'showRegistrationForm')->name('register');
});

//Purchase関連
Route::prefix('purchases')->controller(PurchaseController::class)->group(function () {
    Route::get('/view', 'viewPurchase')->name('viewPurchase'); // GET /api/purchases/view
    Route::get('/form', 'viewPurchaseForm')->name('viewPurchaseForm'); // GET /api/purchases/form
    Route::get('/form/create', 'createPurchaseForm')->name('store'); // POST /api/purchases/create
    Route::get('{purchase}', 'show')->name('show'); // GET /api/purchases/{purchase}
    Route::put('{purchase}', 'update')->name('update'); // PUT /api/purchases/{purchase}
    Route::delete('{purchase}', 'destroy')->name('destroy'); // DELETE /api/purchases/{purchase}
});

//レビュー関連
Route::prefix('reviews')->controller(ReviewController::class)->group(function()  {
    //顧客側
    Route::get('/form', 'reviewForm')->name('reviewForm');
    Route::post('/create', 'createReview')->name('createReview');
    
    //店側
    //レビューを見る
    Route::get('/view', 'viewReviews')->name('viewReviews');
    //Itemごとのレビューを見る
    Route::get('/{reviews}/viewItem', 'viewItemReviews')->name('viewItemReviews');
});

















