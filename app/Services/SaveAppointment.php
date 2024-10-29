<?php

namespace App\Services;
use App\Services\sendNotificationItemNames;
use App\Models\Appointment;
use App\Data\AppointmentData;
use App\Data\AppointmentResult;

//必ずsaveAppointmentsという名前で、AppointmentDataをAppointmentResultとして返す
interface AppointmentSaverInterface
{
    public function saveAppointments(AppointmentData $appointmentData): AppointmentResult;
}

class SaveAppointment implements AppointmentSaverInterface
{
    protected $sendNotificationItemNames;
    /**
     * SaveAppointment constructor
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
        $firstAppointmentTime = null; 
    
        foreach ($appointmentData->itemIds as $index => $itemId) {
            $appointment = new Appointment();
    
            $createAppointments = [
                'item_id' => $itemId,
                'customer_id' => $appointmentData->customerId,
                'staff_id' => $appointmentData->staffId,
                'appointment_date' => $appointmentData->appointmentDate,
                'appointment_time' => $appointmentData->appointmentTime,
            ];

            if ($index === 0) {
                $firstAppointmentTime = $appointmentData->appointmentTime;
            } else {
                $previousAppointment = $appointments[$index - 1];
                $previousDuration = $previousAppointment->item->duration; 
    
                
                $newAppointmentTime = new \DateTime($appointmentData->appointmentTime);
                $newAppointmentTime->add(new \DateInterval("PT{$previousDuration}M")); // 前のdurationを加算
                $createAppointments['appointment_time'] = $newAppointmentTime->format('H:i');
            }
    
            $appointment->fill($createAppointments);
            $appointment->save();
            $appointments[] = $appointment;
        }
    
        // 通知を送信（最初の予約時間を使用）
        $this->sendNotificationItemNames->sendNotificationItemNames($appointments, $appointmentData->customerId, $appointmentData->itemIds, $firstAppointmentTime);
    
        return new AppointmentResult($appointments);
    }
    
}





