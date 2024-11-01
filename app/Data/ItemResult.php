<?php

namespace App\Data;

use App\Models\Item;

class ItemResult
{
    public Item $item;

    /**
     * コンストラクタ
     *
     * @param Item $item アイテム情報を受け取ります
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}


