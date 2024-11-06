<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\ItemPurchase;
use App\Models\Purchase;
use App\Interfaces\ItemSaverInterface;
use App\Data\ItemData;

class ItemController extends Controller
{ 
    protected ItemSaverInterface $itemSaver;

    public function __construct(ItemSaverInterface $itemSaver)
    {
        $this->itemSaver = $itemSaver;
    }

    /**
     * アイテム(サービス内容)を作成する
     *
     * @param StoreItemRequest $request
     * @return JsonResponse
     */
    public function createItem(StoreItemRequest $request)
    {
        $itemData = new ItemData(
            name: $request->name,
            memo: $request->memo,
            price: $request->price,
            is_selling: $request->is_selling,
            duration: $request->duration

        );
        $itemResult = $this->itemSaver->saveItem($itemData);
    
        return response()->json($itemResult->item);
    }
    
    /**
     * アイテム(サービス内容)を変更、更新する
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function updateItem(int $itemsId, UpdateItemRequest $request)
    {
        $itemData = new ItemData(
            name: $request->name,
            memo: $request->memo,
            price: $request->price,
            is_selling: $request->is_selling,
            duration: $request->duration
        );
    
        $itemResult = $this->itemSaver->saveItem($itemData, $itemsId);
    
        return response()->json($itemResult->item);
    }

    /**
     * アイテム(サービス内容)を削除する
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function deleteItem(int $itemId)
    {
        $item = Item::query()->findOrFail($itemId);
        
        if ($item) {
            $item->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }

   /**
    * アイテム詳細を取得する
    *
    * @return \Illuminate\Http\Response
    */
    public function getItemDetail(int $itemsId, Request $request)
    {
        $itemPurchases = ItemPurchase::query()
            ->select('*')
            ->where('item_id', $itemsId)
            ->get();

        $purchaseIds = $itemPurchases->pluck('purchase_id')->unique()->toArray();

        $purchases = Purchase::query()
            ->select('*')
            ->whereIn('purchase_id', $purchaseIds)
            ->get();

        $customerIds = $purchases->pluck('customer_id')->unique()->toArray();

        $customers = Customer::query()
            ->select('*')
            ->whereIn('customer_id', $customerIds)
            ->get();

        $purchaseArray = $purchases->toArray();
        $purchaseArray['customers'] = $customers->toArray();

        return response()->json($purchaseArray);
    }
}
