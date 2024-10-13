<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Rules\ItemsRule;
use Illuminate\Support\Facades\Log;
use App\Models\ItemPurchase;
use App\Models\Purchase;
use GuzzleHttp\Psr7\Query;

class ItemController extends Controller
{  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function viewItems(Request $request)
   {
        $items = Item::query()
        ->select('*')
        ->get();

            return response()->json($items);
   }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewItemCustomers(Request $request)
    {
    $orders = Item::itemCustomers()->take(1000)->get();
  
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
    public function create(StoreItemRequest $request) 
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(int $itemsId, UpdateItemRequest $request)
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
