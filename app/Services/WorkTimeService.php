<?php
namespace App\Services;

use Carbon\Carbon;
use PHPUnit\Framework\Constraint\Count;

class WorkTimeService
{
    /**
     * 勤務時間を計算するメソッド
     * 
     * @param \Illuminate\Database\Eloquent\Collection $loginHistories
     * @return array
     */
    public function calculateTotalWorkTime($loginHistories)
    {
        $totalWorkTimeMinutes = 0;
        $totalWorkDay = Count($loginHistories);

        foreach ($loginHistories as $history) {
            $loginTime = Carbon::parse($history->login_time);
            $logoutTime = Carbon::parse($history->logout_time);

            $workTimeMinutes = $loginTime->diffInMinutes($logoutTime);
            $workTimeMinutes = $this->adjustWorkTimeForBreaks($workTimeMinutes);

            $totalWorkTimeMinutes += $workTimeMinutes;
        }

        $hours = floor($totalWorkTimeMinutes / 60); 
        $minutes = $totalWorkTimeMinutes % 60;

        return ['hours' => $hours, 'minutes' => $minutes, 'totalWorkDay' => $totalWorkDay]; 
    }

    /**
     * 休憩時間を除く労働時間を計算するメソッド
     * 
     * @param int $workTimeMinutes
     * @return int
     */
    private function adjustWorkTimeForBreaks($workTimeMinutes)
    {
        if ($workTimeMinutes >= 540) {
            $workTimeMinutes -= 60;
        } elseif ($workTimeMinutes >= 480) {
            $workTimeMinutes -= 45;
        } elseif ($workTimeMinutes >= 360) {
            $workTimeMinutes -= 30;
        }
        return $workTimeMinutes;    
    }
}
