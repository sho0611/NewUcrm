<?php

namespace App\Data;

class ReviewData
{
    public int $item_id;
    public string $customer_name;
    public int $rating;
    public string $comment;

    /**
     * ReviewData クラスのコンストラクタ
     * 
     * @param int $itemId アイテムのID
     * @param int $customerId 顧客のID
     * @param string $customerName 顧客の名前
     * @param int $rating 評価（例: 1～5のスコア）
     * @param string $comment コメント内容
     */
    public function __construct(int $item_id, string $customer_name, int $rating, string $comment)
    {
        $this->item_id = $item_id;
        $this->customer_name = $customer_name;
        $this->rating = $rating;
        $this->comment = $comment;
    }
}
