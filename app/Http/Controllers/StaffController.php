<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Data\StaffData;
use App\Interfaces\StaffSaverInterface;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\LoginHistory;    
use Carbon\Carbon;
use App\Services\WorkTimeService;   

class StaffController extends Controller
{
    private $staffSaver;
    protected $workTimeService;

    public function __construct(StaffSaverInterface $staffSaver,WorkTimeService $workTimeService)
    {
        $this->staffSaver = $staffSaver;
        $this->workTimeService = $workTimeService;
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

    /**
     * スタッフの勤務時間を取得
     *
     * @param  int  $staffId
     * @return \Illuminate\Http\Response
     */ 
    public function getStaffWorkTime(int $staffId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        $staff = Staff::query()->where('staff_id', $staffId)->first();
        if (!$staff) {
            return response()->json(['message' => 'Staff not found']);
        }
    
        $loginHistories = LoginHistory::query()
            ->where('staff_id', $staffId)
            ->whereMonth('logout_time', $currentMonth) 
            ->whereYear('logout_time', $currentYear)  
            ->get();

        $workTime = $this->workTimeService->calculateTotalWorkTime($loginHistories); 

        $hours = $workTime['hours'];    
        $minutes = $workTime['minutes'];
        $totalWorkDay = $workTime['totalWorkDay'];    

        $staffArray = $staff->toArray();
        $staffArray['loginHistories'] = $loginHistories->toArray();
        $staffArray['totalWorkTime'] = "{$hours}時間{$minutes}分";
        $staffArray['totalWorkDay'] = $totalWorkDay;
    
        return response()->json($staffArray);    
    }
    
}
