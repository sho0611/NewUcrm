<?php

namespace App\Interfaces;

use App\Data\PurchaseData;
use App\Data\PurchaseResult;

interface PurchaseSaverInterface
{
    public function savePurchase(PurchaseData $purchaseData, ?int $purchaseId = null): PurchaseResult;
}
