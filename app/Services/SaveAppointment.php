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

        foreach ($appointmentData->itemIds as $itemId) {
            $appointment = new Appointment();

            $createAppointments = [
                'item_id' => $itemId,
                'customer_id' => $appointmentData->customerId,
                'staff_id' => $appointmentData->staffId,
                'appointment_date' => $appointmentData->appointmentDate,
                'appointment_time' => $appointmentData->appointmentTime,
            ];

            $appointment->fill($createAppointments);
            $appointment->save();
            $appointments[] = $appointment;
        }

        // 通知を送信
        $this->sendNotificationItemNames->sendNotificationItemNames($appointments, $appointmentData->customerId, $appointmentData->itemIds);

        return new AppointmentResult($appointments);
    }
}






