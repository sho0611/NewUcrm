<?php

namespace App\Data;

use App\Models\Staff; 

class StaffResult
{
    public Staff $staff;

    /**
     * コンストラクタ
     *
     * @param Staff $staff スタッフ情報を受け取ります
     */
    public function __construct(Staff $staff)
    {
        $this->staff = $staff;
    }
}



