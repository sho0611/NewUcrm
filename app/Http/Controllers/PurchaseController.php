<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Customer;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Purchase::query();

       
        $orders = $query->leftJoin('item_purchase', 'purchases.id', '=', 'item_purchase.purchase_id')
            ->leftJoin('items', 'item_purchase.item_id', '=', 'items.id')
            ->leftJoin('customers', 'purchases.customer_id', '=', 'customers.id')
            ->groupBy('purchases.id') 
            ->selectRaw('purchases.id as id, SUM(items.price * item_purchase.quantity) as total, customers.name as customer_name,customers.kana as customer_kana ,purchases.status, purchases.created_at')
            ->paginate(50);
            
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customers = Customer::select('id', 'name', 'kana')->get();
        $items = Item::select('id', 'name', 'price')->where('is_selling', true)->get();

        return response()->json([
            'items' => $items,
            'customers' => $customers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase = new Purchase();

        $purchaseCreateArray = [
            'customer_id' => $request->customer_id, 
            'status' => $request->status
        ];

        //purchaseテーブル
        $purchase->fill($purchaseCreateArray);
        $purchase->save();

        //中間テーブル
        $purchase->items()->attach($request->items);

        return response()->json($purchase);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
