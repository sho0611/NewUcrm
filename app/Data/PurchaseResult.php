<?php

namespace App\Data;

use App\Models\Purchase;

class PurchaseResult
{
    public Purchase $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }
}



