<?php

namespace App\Interfaces;

use App\Services\SendNotificationItemNames;

interface SendNotificationItemNamesInterface
{
    public function sendNotificationItemNames($appointment);    
}
