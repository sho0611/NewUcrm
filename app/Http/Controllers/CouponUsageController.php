<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponUsageRequest;
use App\Http\Requests\UpdateCouponUsageRequest;
use App\Models\CouponUsage;
use App\Models\Customer;
use Illuminate\Http\Request;

class CouponUsageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function useCoupon(Request $request)
    {
        $customer_id = $request->customer_id;
        $coupon_id = $request->coupon_id;

        $exstingUsage = CouponUsage::query()
        ->where('customer_id', $customer_id)
        ->where('coupon_id', $coupon_id)
        ->exists();

        if ($exstingUsage) {
            return response()->json(['message' => 'Coupon already used']);
        }

        $couponUsage = new CouponUsage();
        $useCouponArray = [
            'customer_id' => $customer_id,
            'coupon_id' => $coupon_id,
            'used_at' => now()
        ];

        $couponUsage->fill($useCouponArray);
        $couponUsage->save();
        return response()->json($couponUsage);
     }
      /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
     public function viewUsages()
     {
        $uses = CouponUsage::query()
        ->join('coupons', 'coupon_usages.coupon_id', '=', 'coupons.coupon_id')
        ->join('customers', 'coupon_usages.customer_id', '=', 'customers.customer_id')
        ->selectRaw('coupon_usages.usages_id, coupons.code, customers.name, coupon_usages.used_at') 
        ->get();

        return response()->json($uses);
     }
}
