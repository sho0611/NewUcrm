<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Data\StaffData;
use App\Interfaces\StaffSaverInterface;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\LoginHistory;    
use Carbon\Carbon;

class StaffController extends Controller
{
    private $staffSaver;
    public function __construct(StaffSaverInterface $staffSaver)
    {
        $this->staffSaver = $staffSaver;
    }
    /**
     * スタッフ情報の作成
     *
     * @param  \App\Http\Requests\StoreStaffRequest $staff
     * @return \Illuminate\Http\Response
     */
    public function createStaff(StoreStaffRequest $request)
    {
        $staffData = new StaffData(
            name: $request->name,
            memo: $request->memo,
        );
        $postResult = $this->staffSaver->saveStaff($staffData);
        return response()->json($postResult->staff);
    }
    /**
     * スタッフ情報の更新
     *
     * @param  \App\Http\Requests\UpdateStaffRequest  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function updateStaff(int $staffId, UpdateStaffRequest $request)
    {
        $staffData = new StaffData(
            name: $request->name,
            memo: $request->memo,
        );
        $postResult = $this->staffSaver->saveStaff($staffData,$staffId);
        return response()->json($postResult->staff);
    }
    /**
     * スタッフ情報の削除
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function deleteStaff(int $staffId)
    {   
        $staff = Staff::query()->findOrFail($staffId);
        if ($staff) {
            $staff->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }

    public function getStaffWorkTime(int $staffId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $staff = Staff::query()
        ->where('staff_id', $staffId)->get();
        $staffId = $staff->pluck('staff_id'); 
        
        $loginHistories = LoginHistory::query()
        ->where('staff_id', $staffId)
        ->where('logout_time', $currentMonth)
        ->where('login_time', $currentYear)
        ->get();

        $staffArray = $staff->toArray();    
        $staffArray['loginHistories'] = $loginHistories->toArray();
        
        return response()->json($staffArray);   
    }
}
