<?php

namespace App\Data;

class ItemData
{
    public string $name;
    public string $memo;
    public float $price;
    public int $is_selling;
    public int $duration; 

    /**
     * コンストラクタ
     *
     * @param string $name アイテム名
     * @param string $memo メモ
     * @param float $price 価格
     * @param int $is_selling 販売中かどうか
     * @param int $duration 施術時間
     */
    public function __construct(
        string $name,
        string $memo,
        float $price,
        int $is_selling,
        int $duration 
    ) {
        $this->name = $name;
        $this->memo = $memo;
        $this->price = $price;
        $this->is_selling = $is_selling;
        $this->duration = $duration; 
    }
}

