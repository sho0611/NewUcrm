<?php

namespace App\Services;

use App\Models\Appointment;
use App\Data\AppointmentData;
use App\Data\AppointmentResult;
use App\Services\sendNotificationItemNames;
use App\Interfaces\AppointmentSaverInterface;

class SaveAppointment implements AppointmentSaverInterface
{
    protected $sendNotificationItemNames;
    private $firstAppointmentTime;
    /**
     * SendNotificationItemNames constructor
     *
     * @param SendNotificationItemNames $sendNotificationItemNames
     */
    public function __construct(SendNotificationItemNames $sendNotificationItemNames)
    {
       
        $this->sendNotificationItemNames = $sendNotificationItemNames;
    }
    /**
     * 予約を保存
     *
     * @param AppointmentData $appointmentData
     * @return AppointmentResult
     */
    public function saveAppointments(AppointmentData $appointmentData): AppointmentResult
    {
        $appointments = [];

        foreach ($appointmentData->itemIds as $index => $itemId) {
            $appointment = new Appointment();
    
            $createAppointments = [
                'item_id' => $itemId,
                'customer_id' => $appointmentData->customerId,
                'staff_id' => $appointmentData->staffId,
                'appointment_date' => $appointmentData->appointmentDate,
                'appointment_time' => $this->calculateAppointmentTime($appointmentData,
                $appointments, $index)
            ];

            $appointment->fill($createAppointments);
            $appointment->save();
            $appointments[] = $appointment;
        }

        $this->sendNotificationItemNames->sendNotificationItemNames($appointments, $appointmentData->customerId, $appointmentData->itemIds,  $this->firstAppointmentTime);
    
        return new AppointmentResult($appointments);
    }
    /**
     * 複数の予約時に次の予約の時間を計算する
     *
     * @param $appointmentData 予約データを含むオブジェクト
     * @param $appointments これまでに作成された予約オブジェクトの配列
     * @param $index 現在の予約が配列内で何番目であるかのインデックス
     * @return string 次の予約の時間
     */
    private function calculateAppointmentTime($appointmentData, $appointments, $index)
    {
        if ($index === 0) {
            $this->firstAppointmentTime = $appointmentData->appointmentTime;
            return $this->firstAppointmentTime;
        } else {
            $previousAppointment = $appointments[$index - 1];
            $previousDuration = $previousAppointment->item->duration; 
            $newAppointmentTime = new \DateTime($appointmentData->appointmentTime);
            $newAppointmentTime->add(new \DateInterval("PT{$previousDuration}M")); 
            return $newAppointmentTime->format('H:i');    
        }
    }
}





