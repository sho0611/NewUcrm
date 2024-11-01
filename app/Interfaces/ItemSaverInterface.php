<?php

namespace App\Interfaces;

use App\Data\ItemData;
use App\Data\ItemResult;

interface ItemSaverInterface
{
    public function saveItem(ItemData $itemData, ?int $itemId = null): ItemResult;
}
