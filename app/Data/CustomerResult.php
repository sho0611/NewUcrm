<?php

namespace App\Data;

use App\Models\Customer;

class CustomerResult
{
    public Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}




