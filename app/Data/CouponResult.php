<?php

namespace App\Data;

use App\Models\Coupon;

class CouponResult
{
    public Coupon $coupon;

 /**
  * コンストラクタ
  *
  * @param $post クーポンの情報を受け取ります
  */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }
}

