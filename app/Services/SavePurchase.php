<?php

namespace App\Services;

use App\Models\Purchase;
use App\Data\PurchaseData;
use App\Data\PurchaseResult;
use App\Interfaces\PurchaseSaverInterface;

/**
 * 購入内容を保存する
 * idがあれば更新、なければ新規作成
 *
 * @param PurchaseData $purchaseData
 * @param integer|null $purchaseId
 * @return PurchaseResult
 */
class SavePurchase implements PurchaseSaverInterface
{
    public function savePurchase(PurchaseData $purchaseData, ?int $purchaseId = null): PurchaseResult
    {
        if ($purchaseId) {
            $purchase = Purchase::findOrFail($purchaseId);
            if (!$purchase) {
                return response()->json(['error' => 'Purchase not found for ID: ' . $purchaseId]);
            }
        } else {
            $purchase = new Purchase();
        }

        $purchaseCreateArray = [
            'customer_id' => $purchaseData->customer_id,
            'status' => $purchaseData->status
        ];
        
        $purchase->fill($purchaseCreateArray);
        $purchase->save();
        
        foreach ($purchaseData->items as $item) {
            $purchase->items()->attach($item['item_id'], ['quantity' => $item['quantity']]);}

        return new PurchaseResult($purchase);
    }
}

