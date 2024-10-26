<?php

namespace App\Services;
use App\Models\Appointment;
use App\Models\Item;
use App\Models\Customer;
use App\Notifications\AppointmentCreated;
use App\Services\sendNotificationItemNames;

class SaveAppointmentController
{
    protected $sendNotificationItemNames;

    public function __construct(SendNotificationItemNames $sendNotificationItemNames)
    {
        $this->sendNotificationItemNames = $sendNotificationItemNames;
    }
    
    public static function createAppointments(array $itemIds, int $customerId, int $staffId, string $appointmentDate, string $appointmentTime): array
    {
        $appointments = [];

        foreach ($itemIds as $itemId) {
            $appointment = new Appointment();

            $createAppointments = [
                'item_id' => $itemId,
                'customer_id' => $customerId,
                'staff_id' => $staffId,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
            ];

            $appointment->fill($createAppointments);
            $appointment->save();
            $appointments[] = $appointment;
        }

        // 通知とアイテム名取得のメソッド呼び出し
        // $this->sendNotificationItemNames($appointments, $customerId, $itemIds);

        return $appointments;
    }

}



