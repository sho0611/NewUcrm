<?php

namespace App\Interfaces;

use App\Data\CustomerData;
use App\Data\CustomerResult;

interface CustomerSaverInterface
{
    public function saveCustomer(CustomerData $customerData, ?int $customerId = null): CustomerResult;
}
