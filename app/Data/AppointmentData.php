<?php

namespace App\Data;

class AppointmentData
{
    public array $itemIds;
    public int $customerId;
    public int $staffId;
    public string $appointmentDate;
    public string $appointmentTime;

    public function __construct(array $itemIds, int $customerId, int $staffId, string $appointmentDate, string $appointmentTime)
    {
        $this->itemIds = $itemIds;
        $this->customerId = $customerId;
        $this->staffId = $staffId;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
    }
}
