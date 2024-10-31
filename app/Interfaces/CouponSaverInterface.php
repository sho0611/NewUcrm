<?php

namespace App\Interfaces;

use App\Data\CouponData;
use App\Data\CouponResult;

interface CouponSaverInterface
{
    public function saveCoupon(CouponData $couponData, ?int $couponId = null): CouponResult;
}