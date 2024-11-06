<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;




class GestCouponController extends Controller
{
    /*
     * 使用可能なクーポンを表示する
     *
     * @param  \App\Http\Requests\StorecouponRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function viewCoupon(Request $request)
    {
        $coupons = Coupon::query()
            ->select('*')
            ->where('expiration_date', '>', now()) 
            ->where('status', 'active')
            ->get();
        return response()->json($coupons);
    }
}
