<?php

namespace App\Data;


class CouponData
{
    public string $code;
    public int $discount_value;
    public string $expiration_date;
    public string $status;
    /**
     * コンストラクタ
     *
     * @param string $code クーポンコード
     * @param integer $discount_value 割引額
     * @param integer $expiration_date 有効期限
     * @param string $status ステータス
     */
    public function __construct(string $code, int $discount_value, string $expiration_date, string $status)
    {
        $this->code = $code;
        $this->discount_value = $discount_value;
        $this->expiration_date = $expiration_date;
        $this->status = $status;
    }
}
