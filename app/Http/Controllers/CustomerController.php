<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use App\Models\ItemPurchase;

class CustomerController extends Controller
{
     /**
     * 顧客一覧を表示する
     *
     * @return \Illuminate\Http\Response
     */

    public function viewCustomers(Request $request)
    {
        $customers = Customer::query()
        ->select('*')
        ->get();
        return response()->json($customers);
    }

    /**
     * 顧客の詳細を取得する
     *
     * @param integer $customerId
     * @param Request $request
     * @return void
     */
    public function getCustomerDetail(int $customerId, Request $request)
    {
        $purchases = Purchase::query()
        ->select('*')
        ->where('customer_id', $customerId)
        ->get();

        /** @var int[] $purchaseIds*/
        $purchaseIds = array_map(fn($purchases) => $purchases['purchase_id'], $purchases->toArray());

        $itemPurchases = ItemPurchase::query()
        ->select('*')
        ->whereIn('purchase_id', $purchaseIds)
        ->get();

        /** @var int[] $purchaseIds*/
        $itemIds = array_map(fn($itemPurchases):int => $itemPurchases['item_id'], $itemPurchases->toArray());
        $uniqueItemIds = array_unique($itemIds);

        $items = Item::query()
        ->select('*')
        ->whereIn('item_id', $uniqueItemIds)
        ->get();

        $purchaseArray = $purchases->toArray();
        $purchaseArray['items'] = $items->toArray();
        
        return response()->json($purchaseArray);
    }
}    

