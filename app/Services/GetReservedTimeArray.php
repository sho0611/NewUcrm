<?php
namespace App\Services;

use App\Models\Appointment;

class GetReservedTimeArray
{
    /**
     * 予約された時間を配列で取得
     *
     * @param $appointDate
     * @return $reservedTimes
     */
    public function getReservedTime($appointDate): array
    {
        $interval = new \DateInterval('PT15M');
        $appointments = $this->getAppointmentsByDate($appointDate);
    
    $reservedTimes = [];
    foreach ($appointments as $appointment) 
    {
        $appointmentStartTime = $this->getAppointmentStartTime($appointment);
        $itemDuration = $appointment->item->duration;
        $appointmentEndTime = $this->getAppointmentEndTime($appointment,$itemDuration, $appointmentStartTime);
    
        while ($appointmentStartTime < $appointmentEndTime)
        { 
            $roundedTime = $this->roundToNearestQuarterHour($appointmentStartTime);
            $reservedTimes[] = $roundedTime->format('H:i');
            $appointmentStartTime->add($interval);
        }
    
    }
    $reservedTimes = array_unique($reservedTimes);
    $reservedTimes = array_values($reservedTimes);
    return $reservedTimes;
}
    /**
     * 時間を四半期単位（15分ごと）に丸める
     *
     * @param \DateTime $reservedTime - 丸める対象の時間
     * @return \DateTime - 丸められた時間
     */
    private function roundToNearestQuarterHour($reservedTimes) {
        $minutes = $reservedTimes->format('i');
        $roundedMinutes = round($minutes / 15) * 15;
    
        return $reservedTimes->setTime($reservedTimes->format('H'), $roundedMinutes);
    }

    /**
     * 検索された日付の予約を取得
     *
     * @param string $appointDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAppointmentsByDate($appointDate) {
       $appointments = Appointment::with('item')
        ->where('appointment_date', $appointDate)
        ->get();

        return $appointments;
    }

    /**
     * 予約の開始時間の取得
     *
     * @param $appointment
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAppointmentStartTime($appointment)
    {
        $appointmentStartTime =
        new \DateTime($appointment->appointment_time);
        return $appointmentStartTime;
    }

        /**
     * 予約の開始時間の取得
     *
     * @param $appointment
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAppointmentEndTime($appointment, $itemDuration,
    $appointmentStartTime)
    {
        $appointmentEndTime = clone $appointmentStartTime;
        $appointmentEndTime->add(new \DateInterval("PT{$itemDuration}M"));
        return $appointmentEndTime;
    }
}
