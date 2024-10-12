<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Rules\ItemsRule;
use Illuminate\Support\Facades\Log;



class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $items = Item::itemCustomers()->take(10)->get();
  
    $orders = Item::query()
    ->leftJoin('item_purchase', 'items.id', '=', 'item_purchase.item_id')
    ->leftJoin('purchases', 'item_purchase.purchase_id', '=', 'purchases.id')
    ->leftJoin('customers', 'purchases.customer_id', '=', 'customers.id')
    ->selectRaw('
        items.id AS item_id,
        items.name AS item_name,
        items.price AS item_price,
        item_purchase.id AS pivot_id,
        item_purchase.item_id AS item_purchase_id,
        item_purchase.quantity AS item_quantity,
        purchases.id AS purchase_id,
        purchases.customer_id AS purchase_customer_id,
        customers.id AS customer_id,
        customers.name AS customer_name,
        customers.kana AS customer_kana,
        customers.tel AS customer_tel
    ')->take(10)->get();

$itemOrders = $orders->groupBy('item_id')->map(function ($group) {
    $firstItem = $group->first();

    return [
        'item_id' => $firstItem->item_id,
        'name' => $firstItem->item_name, 
        'customers' => $group->map(function ($customer) {
            return [
                'customer_id' => $customer->customer_id,
                'customer_name' => $customer->customer_name,
                'customer_kana' => $customer->customer_kana, 
                'customer_tel' => $customer->customer_tel,
            ];
        }),
    ];
})->values();
return response()->json($itemOrders);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {

        $request->validate([
            'name' => [new ItemsRule(true)], 
            'memo' => [new ItemsRule(true)],  
            'price' => [new ItemsRule(true)],  
            'is_selling' => [new ItemsRule(true)],
        ]);
    
        $item = new Item();

        $itemCreateArray = [
            'name' => $request->name,
            'memo' => $request->memo,
            'price' => $request->price,
            'is_selling' => $request->is_selling
        ];
    
        
        $item->fill($itemCreateArray);
        $item->save();
    
      
        return response()->json($item);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */

public function store(Request $request)
{
    $item = new Item();

    $itemCreateArray = [
        'name' => $request->name,
        'memo' => $request->memo,
        'price' => $request->price,
        'is_selling' => $request->is_selling
    ];

    
    $item->fill($itemCreateArray);
    $item->save();

  
    return response()->json($item);
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return response()->json($item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(int $itemsId, Request $request)
    {
        $item = Item::query()->findOrFail($itemsId);

        $itemupdateArry = [
            'name' => $request->name,
            'memo' => $request->memo,
            'price' => $request->price,
            'is_selling' => $request->is_selling
        ];
        $item->fill($itemupdateArry)->save();
        return response()->json($item); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json($item);
    }
}
