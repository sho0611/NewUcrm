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
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponUsageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\gestCustomerController;
use App\Http\Controllers\GestItemController;
use App\Http\Controllers\GestAppointmentController;
use App\Http\Controllers\GestCouponController;
use App\Http\Controllers\GestPostController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\GestStaffController;   
use App\Http\Controllers\StripePaymentsController;
use App\Http\Controllers\PayPayController;  


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

//管理書側 CRUD
Route::post('logIn', [AuthController ::class, 'logIn'])->name('logIn');
Route::group(['middleware' => ['auth:sanctum']], function () {

    //商品関連
    Route::prefix('items')->controller(ItemController::class)->group(function () {
        Route::get('create', 'createItem')->name('createItem');       
        Route::put('{item}', 'updateItem')->name('updateItem');   
        Route::delete('{item}', 'deleteItem')->name('deleteItem'); 
        Route::get('/{item}', 'getItemDetail')->name('getItemDetail');
    });

    //顧客関連
    Route::prefix('customers')->controller(CustomerController::class)->group(function () {
        Route::get('/view', 'viewCustomers')->name('viewCustomers');
        Route::get('/{customers}', 'getCustomerDetail')->name('getCustomerDetail');
    });

    //購買履歴関連
    Route::prefix('purchases')->controller(PurchaseController::class)->group(function () {
        Route::get('/view', 'viewPurchase')->name('viewPurchase'); 
        Route::get('/create', 'createPurchase')->name('createPurchaseForm');
        Route::put('{purchase}', 'updatePurchase')->name('updatePurchase'); 
        Route::delete('{purchase}', 'destroyPurchase')->name('destroyPurchas');
    });

    //予約関連
    Route::prefix('Appointments')->controller(AppointmentController::class)->group(function()  {
        Route::get('view', 'view')->name('view');
        Route::get('search/item', 'searchAppointmentItem')->name('searchAppointmentItem');
        Route::get('search/date', 'searchAppointmentsByDateWithItems')->name('AppointmentsByDateWithItems');
    });

    //クーポン関連
    Route::prefix('coupon')->controller(CouponController::class)->group(function () {
        Route::get('/create', 'createCoupon')->name('createCoupon');
        Route::put('{coupon}', 'updateCoupon')->name('updateCoupon');
        Route::delete('{coupon}', 'destroyCoupon')->name('destroyCoupon');
    });

    //履歴 
    Route::prefix('usecoupon')->controller(CouponUsageController::class)->group(function() {
        Route::get('/view', 'viewUsages')->name('viewUsages');
    });

    //SNS //投稿
    Route::prefix('sns')->controller(PostController::class)->group(function() {
        Route::post('/post', 'post')->name('post');
        Route::post('{sns}', 'updatePost')->name('postUpdate');
        Route::delete('{sns}', 'deletePost')->name('postDestroy'); 
    });

    //スタッフ関連
    Route::prefix('staffs')->controller(StaffController::class)->group(function () {
        Route::get('create', 'createStaff')->name('createStaff');
        Route::put('{staffs}', 'updateStaff')->name('updateStaff');
        Route::delete('{staffs}', 'deleteStaff')->name('deleteStaff');
        
        Route::get('work/{staffs}', 'getStaffWorkTime')->name('getStaffWorkTime');  
    }); 

    Route::post('/logout', [AuthController::class, 'logout']);
});

//ここからユーザー側 CRUD
//売り上げ分析 日/月/年
Route::prefix('analysis')->controller(AnalysisController::class)->group(function () {
    Route::get('/day', 'analysisDay')->name('analysisDay');
    Route::get('/mouth', 'analysisMouth')->name('analysisMonth');
    Route::get('/year', 'analysisYear')->name('analysisYear');
});

Route::post('/payment', [StripePaymentsController::class, 'payment']);




Route::prefix('paypay')->as('paypay')->group(function () {
    Route::post('/payment', [PayPayController::class, 'payment'])->name('payment');
    Route::post('/webhook', [PayPayController::class, 'webhook']);
});



//データ分析 デシル
Route::get('desile', [AnalysisDesileController::class, 'desile']);

//データ分析 RFM
Route::get('rfm', [AnalysisRfmController::class, 'rfm']);

//商品関連 
Route::get('viewItems', [GestItemController::class, 'viewItems'])->name('viewItems');

//顧客関連
Route::prefix('customers')->controller(GestCustomerController::class)->group(function () {
    Route::get('create', 'createCustomer')->name('createCustome');  
    Route::put('{customers}', 'updateCustomer')->name('updateCustomer');
    Route::delete('{customers}', 'deleteCustomer')->name('deleteCustomer');
});

//レビュー関連
Route::prefix('reviews')->controller(ReviewController::class)->group(function()  {
    Route::get('/create', 'createReview')->name('createReview');
    Route::put('/{reviews}', 'updateReviews')->name('updateReviews');
    Route::delete('/{reviews}', 'deleteReviews')->name('deleteReviews');
    Route::get('/view', 'viewReviews')->name('viewReviews');
    Route::get('/{reviews}', 'viewItemReviews')->name('viewItemReviews');    
});

//スタッフ関連
Route::get('staffs/view', [GestStaffController::class, 'viewStaff'])->name('viewStaff');  


//予約関係
Route::prefix('app')->controller(GestAppointmentController::class)->group(function()  {
    Route::get('/create', 'createAppointment')->name('createAppointment');
    Route::get('times', 'getAvailableTimes')->name('getAvailableTimes');
    Route::put('/{app}', 'changAppointment')->name('changAppointment');
    Route::delete('/{app}', 'deleteAppointment')->name('deleteAppointment');
});

//クーポン関連
Route::get('coupon/view', [GestCouponController::class, 'viewCoupon'])->name('viewCoupon');

//クーポン利用
Route::get('usecoupon/use', [CouponUsageController::class, 'useCoupon'])->name('useCoupon');

//SNS //投稿
Route::prefix('sns')->controller(GestPostController::class)->group(function() {
    Route::get('/view', 'viewPosts')->name('viewPosts');
    Route::get('/{postId}', 'countLikesbyPost')->name('countLikesbyPost');
    Route::get('/comment/{postId}', 'getPostComments')->name('getPostComments');
    Route::get('/item/{itemId}', 'getItemPost')->name('getItemPost');
});

//SNS //いいね
Route::prefix('likes')->controller(LikeController::class)->group(function() {
    Route::post('/post', 'postLike')->name('postLike');
    Route::delete('/{likes}', 'deleteLike')->name('deleteLike');
});

//SNS //コメント
Route::prefix('comments')->controller(CommentController::class)->group(function() {
    Route::post('/post', 'postComment')->name('postComment');
    Route::put('/{com}', 'updateComment')->name('updateComment');
    Route::delete('/{com}', 'deleteComment')->name('deleteComment');
});
























