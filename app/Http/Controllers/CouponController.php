<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecouponRequest;
use App\Http\Requests\UpdatecouponRequest;
use App\Models\Coupon;
use App\Data\CouponData;
use Illuminate\Http\Request;
use App\Interfaces\CouponSaverInterface;



class CouponController extends Controller
{
    protected $couponSaver;
    public function __construct(CouponSaverInterface $couponSaver)
    {
        $this->couponSaver = $couponSaver;
    }

    /**
     * クーポンを作成する
     *
     * @return \Illuminate\Http\Response
     */
    public function createCoupon(StorecouponRequest $request)
    {
        $couponData = new CouponData(
            code: $request->code,
            discount_value: $request->discount_value,
            expiration_date: $request->expiration_date,
            status: $request->status
        );

        $couponResult = $this->couponSaver->saveCoupon($couponData);
    
        return response()->json($couponResult->coupon);
    }

        /**
     * クーポンの内容を変更、更新する
     *
     * @param  \App\Http\Requests\UpdatecouponRequest  $request
     * @param  \App\Models\coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function updateCoupon(int $couponsId,UpdatecouponRequest $request)
    {

        $couponData = new CouponData(
            code: $request->code,
            discount_value: $request->discount_value,
            expiration_date: $request->expiration_date,
            status: $request->status
        );

        $couponResult = $this->couponSaver->saveCoupon($couponData, $couponsId);
    
        return response()->json($couponResult->coupon);        
    }

    /**
     * クーポンを削除する
     *
     * @param  \App\Models\coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroyCoupon($couponsId)
    {
        $coupon = Coupon::query()->findOrFail($couponsId);
        if ($coupon) {
            $coupon->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
}
