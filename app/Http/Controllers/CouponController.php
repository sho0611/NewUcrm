<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecouponRequest;
use App\Http\Requests\UpdatecouponRequest;
use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\TopCouponNotification;



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


    //これはどこに書くのが適切か  //テストコード
    public function sendCouponToTopCustomers()
    {
        $towMonthsago = now()->subMonth(2);

        $topCustomers = Customer::query()
        //直近2ヶ月の八百屋くを取得
        ->where('appointment_date', '>=', $towMonthsago)
        ->groupBy('customer_id')
        //予約が2回以上の顧客を取得
        ->havingRaw('count(*) >= 2')
        ->pluck('customer_id');
        
        //送るクーポンを取得
        $coupon = Coupon::where('discount_value', '50')
        ->where('expiration_date', '>', Carbon::now()) 
        ->first();

        if ($coupon) {
            $customers = Customer::whereIn('customer_id', $topCustomers)->get();
            foreach ($customers as $customer)
            {  
                $customer->notify(new TopCouponNotification($coupon));
                return response()->json(['message' => 'Coupon sent successfully!']);
            }
        }
    }
}
