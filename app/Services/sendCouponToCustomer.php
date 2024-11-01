<?php

namespace App\Services;
use App\Models\Coupon;
use Carbon\Carbon;
use App\Notifications\CouponNotification;

/**
 * 顧客にクーポンを送信する
 */
class sendCouponToCustomer 
{
   public function sendCouponToCustomer($customer)
   {
        $coupon = Coupon::where('discount_value', '50')
        ->where('expiration_date', '>', Carbon::now())
        ->first();

        if ($coupon) {
            $customer->notify(new CouponNotification($coupon));
        }
    }
}