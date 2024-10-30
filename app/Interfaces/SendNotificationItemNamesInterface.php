<?php

namespace App\Interfaces;

use App\Services\SendNotificationItemNames;

interface SendNotificationItemNamesInterface
{
    public function sendNotificationItemNames(array $appointments, int $customerId, array $itemIds, string $firstAppointmentTime);
}
