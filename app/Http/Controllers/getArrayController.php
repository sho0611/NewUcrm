<?php

namespace App\Http\Controllers;


use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class getArrayController extends Controller
{
    public function index(Request $request)
    {
   
    $customers = Customer::limit(1000)->get()->toArray();
    $items = Item::all()->toArray();
    $purchases = Purchase::limit(1000)->get()->toArray();
    $itemPurchases = DB::table('item_purchase')->take(1000)->get()->toArray();
    //dd($itemPurchases);

$result = [];
    
foreach ($customers as $customer) {
    foreach ($purchases as $purchase) {
        if ($customer['id'] == $purchase['customer_id']) {
            foreach ($itemPurchases as $itemPurchase) {
                if($itemPurchase->purchase_id === $purchase['id']) 
                foreach ($items as $item){
            if($item['id'] === $itemPurchase->item_id)
            {
                $result[] = [
                    'customer_id' => $customer['id'],
                    'customer_name' => $customer['name'],
                    'customer_kana' => $customer['kana'],
                    'purchase_id' => $purchase['id'],
                    'purchase_customer_id' => $purchase['customer_id'],
                    'pivot_item_id' => $itemPurchase->item_id, 
                    'quantity' => $itemPurchase->quantity, 
                    'pivot_id' => $itemPurchase->purchase_id, 
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'item_price' => $item['price']
                 ];
                }
              }
            }
        }
    }
}


    return response()->json($result);

}
}
