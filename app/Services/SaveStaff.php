<?php

namespace App\Services;

use App\Models\Staff;  
use App\Data\StaffData;  
use App\Data\StaffResult; 
use App\Interfaces\StaffSaverInterface; 

class SaveStaff implements StaffSaverInterface
{
    /**
     * スタッフ情報を保存する
     * idがあれば更新、なければ新規作成
     *
     * @param StaffData $staffData
     * @param integer|null $staffId
     * @return StaffResult
     */
    public function saveStaff(StaffData $staffData, ?int $staffId = null): StaffResult
    {
        if ($staffId) {
            $staff = Staff::findOrFail($staffId);
        } else {
            $staff = new Staff();
        }
        
        $createStaffArray = [
            'name' => $staffData->name,
            'memo' => $staffData->memo,
        ];

        $staff->fill($createStaffArray);
        $staff->save();
        return new StaffResult($staff);
    }
}
