<?php

namespace App\Services;

use App\Models\Item;
use App\Data\ItemData;
use App\Data\ItemResult; 
use App\Interfaces\ItemSaverInterface; 
class SaveItem implements ItemSaverInterface
{
    /**
     * アイテムの内容を保存する
     * idがあれば更新、なければ新規作成
     *
     * @param ItemData $itemData
     * @param integer|null $itemId
     * @return ItemResult
     */
    public function saveItem(ItemData $itemData, ?int $itemId = null): ItemResult
    {
        if ($itemId) {
            $item = Item::findOrFail($itemId);
            if (!$item) {
                return response()->json(['error' => 'Item not found for ID: ' . $itemId]);
            }
        } else {
            $item = new Item();
        }
        $itemCreateArray = [
            'name' => $itemData->name,
            'memo' => $itemData->memo,
            'price' => $itemData->price,
            'is_selling' => $itemData->is_selling,
            'duration' => $itemData->duration
        ];

        $item->fill($itemCreateArray);
        $item->save();

        return new ItemResult($item); 
    }
}
