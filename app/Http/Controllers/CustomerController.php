<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\ItemPurchase;



class CustomerController extends Controller
{
     /**
     * Display a listing of the resource.
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCustomerItems(Request $request)
    {
        //直接書くやり方
    $orders = Customer::query()
    ->leftJoin('purchases', 'customers.id', '=', 'purchases.customer_id')
    ->leftJoin('item_purchase', 'purchases.id', '=', 'item_purchase.purchase_id')
    ->leftJoin('items', 'item_purchase.item_id', '=', 'items.id')
    ->selectRaw('customers.id AS customer_id, customers.name as customer_name, customers.kana as customer_kana, items.name AS item_name, items.price AS item_price, item_purchase.quantity, items.memo AS item_memo')
    ->get();

    //$orders = Customer::customerItems()->take(10)->get();

    $groupedOrders = $orders->groupBy('customer_id')->map(function ($customer) {
        $firstCustomer = $customer->first(); 
        return [
            'customer_id' => $firstCustomer->customer_id,
            'name' => $firstCustomer->customer_name, 
            'kana' => $firstCustomer->customer_kana, 
            'items' => $customer->map(function ($item) {
                return [
                    'item_name' => $item->item_name,
                    'item_price' => $item->item_price,
                    'quantity' => $item->quantity,
                    'memo' => $item->item_memo, 
                ];
            }),
        ];
    })->values();
    
    return response()->json($groupedOrders);
}


    /**
     * Retrieve the details of a specific customer, including their purchases and associated items.
     *
     * This method queries the database for the purchases made by a specific customer,
     * retrieves the associated item purchases, and then gathers the details of those items.
     *
     * @param int $customerId The ID of the customer whose details are being retrieved.
     * @param Request $request The HTTP request instance.
     * @return JsonResponse A JSON response containing the customer's purchase details and associated items.
     */
    public function getCustomerDetail(int $customerId, Request $request)
    {
        // idの購入履歴を取得
        $purchases = Purchase::query()
        ->select('*')
        ->where('customer_id', $customerId)
        ->get();

        /** @var int[] $purchaseIds*/
        //purchases.idを配列にして取得
        $purchaseIds = array_map(fn($purchases) => $purchases['id'], $purchases->toArray());
        //  $purchaseIds = $purchases->pluck('id')->toArray();

        $itemPurchases = ItemPurchase::query()
        ->select('*')
        ->whereIn('purchase_id', $purchaseIds)
        ->get();

        /** @var int[] $purchaseIds*/
        //item_idを配列にして取得
        $itemIds = array_map(fn($itemPurchases):int => $itemPurchases['item_id'], $itemPurchases->toArray());
        $uniqueItemIds = array_unique($itemIds);

        $items = Item::query()
        ->select('*')
        ->whereIn('id', $uniqueItemIds)
        ->get();

        $purchaseArray = $purchases->toArray();
        //新しいキーitemsを追加し、itemsの配列を代入
        $purchaseArray['items'] = $items->toArray();
        
        return response()->json($purchaseArray);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreCustomerRequest $request)
    {
        $customer = new Customer();

        $customerCreateArray = [

            'name' => $request->name,
            'kana' => $request->kana,
            'tel' => $request->tel,
            'email' => $request->email,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'memo' => $request->memo,
        ];
        $customer->fill($customerCreateArray);
        $customer->save();

        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(int $customerId,UpdateCustomerRequest $request)
    {
        $customer = Customer::query()->find($customerId);

        $customerUpdateArray = [
            'name' => $request->name,
            'kana' => $request->kana,
            'tel' => $request->tel,
            'email' => $request->email,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'memo' => $request->memo,
        ];
        $customer->fill($customerUpdateArray)->save();

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json($customer);
    }
}
