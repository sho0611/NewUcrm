<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecouponRequest;
use App\Http\Requests\UpdatecouponRequest;
use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\Customer;
use Illuminate\Http\Request;



class CouponController extends Controller
{
       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCoupon(StorecouponRequest $request)
    {
        $item = new coupon();

        $couponCreateArray = [
            'code' => $request->code,
            'discount_value' => $request->discount_value,
            'expiration_date' => $request->expiration_date,
            'status' => $request->status
        ];
    
        $item->fill($couponCreateArray);
        $item->save();
    
        return response()->json($couponCreateArray);
        
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecouponRequest  $request
     * @param  \App\Models\coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function updateCoupon(int $couponsId,UpdatecouponRequest $request)
    {
        //$coupon = Coupon::query()->findOrFail($couponId);
        //$coupon = Coupon::query()->findOrFail('coupon_id',$couponsId);
        //$coupon = Coupon::where('coupon_id', $couponsId)->firstOrFail();

        $coupon = Coupon::where('coupon_id', $couponsId)->get();

        //$coupon = Coupon::where('coupon_id', $couponsId)->firstOrFail();

        $couponUpdateArray = [
            'code' => $request->code,
            'discount_value' => $request->discount_value,
            'expiration_date' => $request->expiration_date,
            'status' => $request->status,
        ];
        $coupon->fill($couponUpdateArray)
        ->save();

        return response()->json($coupon);         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroyCoupon(coupon $coupon)
    {
        $coupon->delete();
        return response()->json($coupon);
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecouponRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function viewCoupon(StoreCouponRequest $request)
    {
        $coupons = Coupon::query()
            ->select('*')
            ->where('expiration_date', '>', now()) 
            ->where('status', 'active')
            ->get();
        return response()->json($coupons);
    }

    public function sendCouponToTopCustomers()
    {
        
    }

}
