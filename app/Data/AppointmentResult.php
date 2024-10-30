<?php

namespace App\Data;

class AppointmentResult
{
    
    public array $appointments;

    /**
     * AppointmentResult constructor.
     *
     * @param array $appointments 予約の配列を受け取ります。
     */
    public function __construct(array $appointments)
    {
        $this->appointments = $appointments;
    }
}
