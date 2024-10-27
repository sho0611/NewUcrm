<?php

namespace App\Services;
use App\Models\Item;
use App\Models\Customer;
use App\Services\sendNotificationItemNames;
use App\Models\Appointment;
use App\Data\AppointmentData;
use App\Data\AppointmentResult;

class SaveAppointment
{
    protected $sendNotificationItemNames;

    public function __construct(SendNotificationItemNames $sendNotificationItemNames)
    {
        $this->sendNotificationItemNames = $sendNotificationItemNames;
    }
    
    public function createAppointments(AppointmentData $appointmentData): AppointmentResult
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

        $this->sendNotificationItemNames->sendNotificationItemNames($appointments, $appointmentData->customerId, $appointmentData->itemIds);

        return new AppointmentResult($appointments);
    }
}





