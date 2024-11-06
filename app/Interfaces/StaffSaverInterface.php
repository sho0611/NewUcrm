<?php

namespace App\Interfaces;

use App\Data\StaffData;
use App\Data\StaffResult;

interface StaffSaverInterface
{
    public function saveStaff(StaffData $staffData, ?int $staffId = null): StaffResult;
}
