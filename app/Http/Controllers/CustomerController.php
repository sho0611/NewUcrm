<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\DB;


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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
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
