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
    //顧客側 //レビュー作成関係
    Route::get('/form', 'reviewForm')->name('reviewForm');
    Route::post('/create', 'createReview')->name('createReview');
   
    //レビュー閲覧 //レビューを見る
    Route::get('/view', 'viewReviews')->name('viewReviews');
    //itemごとのレビューを見る
    Route::get('/{reviews}/viewItem', 'viewItemReviews')->name('viewItemReviews');
});



//管理ログイン
Route::post('/admin-login', [AdminLoginController::class, 'logIn'])->name('admin.login.store');
//管理ログアウト
Route::delete('/admin-login', [AdminLoginController::class, 'logOut'])->name('admin.login.destroy');


//予約関係
Route::prefix('app')->controller(AppointmentController::class)->group(function()  {
    // 顧客側
    // それぞれのidに紐ずくデータを取得しjson送信
    Route::get('/form', 'AppointmentForm')->name('AppointmentForm');
    // 予約フォーム作成
    Route::get('/create', 'createAppointment')->name('createAppointment');
    // 予約可能時間の表示
    Route::get('available-times', 'getAvailableTimes')->name('getAvailableTimes');
    
    //http://127.0.0.1:8000/api/app/admin/view
    // 管理者側
    Route::middleware('auth:admin')->group(function () {
        // 店側の予約検索
        Route::get('view', 'view')->name('view');

        Route::get('search/service', 'searchAppointmentService')->name('searchAppointmentService');
        Route::get('search/date', 'searchAppointmentDay')->name('searchAppointmentDay');
        Route::get('search', 'searchDayItem')->name('searchDayItem');
    });
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
//クーポン利用、履歴
Route::prefix('usecoupon')->controller(CouponUsageController::class)->group(function() {
    //顧客側
    Route::get('/use', 'useCoupon')->name('useCoupon');

    //管理者側
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
    // Route::get('likes/{sns}', 'likeCount')->name('likeCount');
    // Route::get('comment/{sns}', 'viewComment')->name('viewComment');
    // Route::get('item/{sns}', 'viewItemPost')->name('viewItemPost');
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
    Route::put('/{comments}', 'updateComment')->name('updateComment');
    Route::delete('/{comments}', 'deleteComment')->name('deleteComment');
});






















