<?php

namespace App\Data;

class PurchaseData
{
    public int $customer_id;
    public string $status;
    public array $items;

    /**
     * PurchaseData クラスのコンストラクタ
     * 
     * @param int $customerId 顧客のID
     * @param string $status ステータス
     */
    public function __construct(int $customer_id, string $status, array $items)
    {
        $this->customer_id = $customer_id;
        $this->status = $status;
        $this->items = $items;
    }
}
