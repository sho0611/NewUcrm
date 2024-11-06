<?php

namespace App\Data;

namespace App\Data;

class StaffData
{
    public string $name;   // スタッフの名前
    public string $memo;   // メモ

    /**
     * StaffData クラスのコンストラクタ
     * 
     * @param string $name スタッフの名前
     * @param string $memo スタッフに関するメモ
     */
    public function __construct(string $name, string $memo)
    {
        $this->name = $name;
        $this->memo = $memo;
    }
}
