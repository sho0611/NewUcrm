<?php

namespace App\Data;

use App\Models\Staff;

class PostData
{
    public string $path;
    public int $staff_id;
    public int $item_id;
    public string $description;

    /**
     * PostData クラスのコンストラクタ
     *
     * @param string $path 画像パス
     * @param integer $staff_id スタッフのID
     * @param integer $item_id アイテムのID
     * @param string $description 投稿の説明
     */
    public function __construct(string $path,int $staff_id,int $item_id,string $description)
    {
        $this->path = $path;
        $this->staff_id = $staff_id;
        $this->item_id = $item_id;
        $this->description = $description;
    }
}