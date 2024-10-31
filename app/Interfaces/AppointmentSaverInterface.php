<?php

namespace App\Interfaces;

use App\Data\AppointmentData;
use App\Data\AppointmentResult;

interface AppointmentSaverInterface
{
    public function saveAppointments(AppointmentData $appointmentData, ?int $appId = null): AppointmentResult;
}