<?php

namespace App\Services;
use App\Models\Coupon;
use App\Data\CouponData;
use App\Data\CouponResult;
use App\Interfaces\CouponSaverInterface;


class SaveCoupon implements CouponSaverInterface
{
    /**
     * コメントを保存する
     *
     * @param CommentData $commentData
     * @param integer|null $commentId
     * @return CommentResult
     */
    public function saveCoupon(CouponData $couponData, ?int $couponId = null): CouponResult
    {
        if ($couponId) {
            $coupon = Coupon::findOrFail($couponId);
            if (!$coupon) {
                return response()->json(['error' => 'Coupon not found for ID: ' . $couponId]);
            }
        } else {
            $coupon = new Coupon();
        }
    
        $couponCreateArray = [
            'code' => $couponData->code,
            'discount_value' => $couponData->discount_value,
            'expiration_date' => $couponData->expiration_date,
            'status' => $couponData->status
        ];
    
        $coupon->fill($couponCreateArray);
        $coupon->save();
    
        return new CouponResult($coupon);
    }
}