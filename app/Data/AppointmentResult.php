<?php

namespace App\Data;

use App\Models\Appointment;

class AppointmentResult
{
    public array $appointments;
    public function __construct(array $appointments)
    {
        $this->appointments = $appointments;
    }
}
