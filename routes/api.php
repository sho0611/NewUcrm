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
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponUsageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
//use App\Http\Controllers\Auth\StaffController;



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
    Route::get('/month', 'analysisMouth')->name('analysisMonth');
    Route::get('/year', 'analysisYear')->name('analysisYear');
});

//データ分析 デシル
Route::get('desile', [AnalysisDesileController::class, 'index']);

//データ分析 RFM
Route::get('analysisRfm', [AnalysisRfmController::class, 'index']);

//item関連
Route::prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('create', 'createItem')->name('createItem');       
    Route::put('{item}', 'updateItem')->name('updateItem');   
    Route::delete('{item}', 'deleteItem')->name('eleteItem'); 

    Route::get('/view', 'viewItems')->name('viewItems'); 
    Route::get('/{item}', 'getItemDetail')->name('getItemDetail');
});

//customer関連
Route::prefix('customers')->controller(CustomerController::class)->group(function () {
    Route::get('create', 'createCustomer')->name('createCustome');  
    Route::put('{customers}', 'updateCustomer')->name('updateCustomer');
    Route::delete('{customers}', 'deleteCustomer')->name('deleteCustomer');

    Route::get('/view', 'viewCustomers')->name('viewCustomers');
    Route::get('/{customers}', 'getCustomerDetail')->name('getCustomerDetail');
});

//Purchase関連
Route::prefix('purchases')->controller(PurchaseController::class)->group(function () {
    Route::get('/view', 'viewPurchase')->name('viewPurchase'); 
    Route::get('/create', 'createPurchase')->name('createPurchaseForm');
    Route::put('{purchase}', 'updatePurchase')->name('updatePurchase'); 
    Route::delete('{purchase}', 'destroyPurchase')->name('destroyPurchas');
});

//レビュー関連
Route::prefix('reviews')->controller(ReviewController::class)->group(function()  {
    Route::get('/create', 'createReview')->name('createReview');
    Route::put('/{reviews}', 'updateReviews')->name('updateReviews');
    Route::delete('/{reviews}', 'deleteReviews')->name('deleteReviews');

    Route::get('/view', 'viewReviews')->name('viewReviews');
    Route::get('/{reviews}', 'viewItemReviews')->name('viewItemReviews');
    
});

//管理ログイン
Route::post('/admin-login', [AdminLoginController::class, 'logIn'])->name('admin.login.store');

//予約関係
Route::prefix('app')->controller(AppointmentController::class)->group(function()  {
    // 顧客側
    Route::get('/create', 'createAppointment')->name('createAppointment');
    Route::get('times', 'getAvailableTimes')->name('getAvailableItems');
    Route::get('get', 'getAvailableStaffItems')->name('getAvailableItems');
    Route::put('/{app}', 'changAppointment')->name('changAppointment');
    Route::delete('/{app}', 'deleteAppointment')->name('deleteAppointment');
    
    // 管理者側
    // Route::middleware('auth:sanctum')->group(function () {
        // 店側の予約検索
        Route::get('view', 'view')->name('view');

        Route::get('search/item', 'searchAppointmentItem')->name('searchAppointmentItem');
        Route::get('search/date', 'searchAppointmentsByDateWithItems')->name('AppointmentsByDateWithItems');
        //管理ログアウト
        // Route::delete('/admin-login', [AdminLoginController::class, 'logOut'])->name('admin.login.destroy');
    // });
});

//クーポン関連
Route::prefix('coupon')->controller(CouponController::class)->group(function () {
    // 管理者側
    Route::get('/create', 'createCoupon')->name('createCoupon');
    Route::put('{coupon}', 'updateCoupon')->name('updateCoupon');
    Route::delete('{coupon}', 'destroyCoupon')->name('destroyCoupon');
    
    // 顧客側
    Route::get('/view', 'viewCoupon')->name('viewCoupon');
});

//クーポン利用、履歴 //後回し
Route::prefix('usecoupon')->controller(CouponUsageController::class)->group(function() {
    Route::get('/use', 'useCoupon')->name('useCoupon');
    Route::get('/view', 'viewUsages')->name('viewUsages');
});

//SNS //投稿
Route::prefix('sns')->controller(PostController::class)->group(function() {
    //管理者側
    Route::post('/post', 'post')->name('post');

    //ユーザー側
    Route::post('{sns}', 'updatePost')->name('postUpdate');
    Route::delete('{sns}', 'deletePost')->name('postDestroy'); 
    // Route::get('/view', 'viewPosts')->name('viewPosts');
    // Route::get('/{sns}', 'likeCount')->name('likeCount');
    // Route::get('/{sns}', 'viewComment')->name('viewComment');
    // Route::get('/{sns}', 'viewItemPost')->name('viewItemPost');
});

//SNS //いいね
Route::prefix('likes')->controller(LikeController::class)->group(function() {
    //顧客側
    Route::post('/post', 'postLike')->name('postLike');
    Route::delete('/{likes}', 'deleteLike')->name('deleteLike');
});
//SNS //コメント
Route::prefix('com')->controller(CommentController::class)->group(function() {
    Route::post('/post', 'postComment')->name('postComment');
    Route::put('/{com}', 'updateComment')->name('updateComment');
    Route::delete('/{com}', 'deleteComment')->name('deleteComment');
});






















