<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use App\Models\ItemPurchase;
use App\Interfaces\CustomerSaverInterface;
use App\Data\CustomerData;



class CustomerController extends Controller
{
    protected CustomerSaverInterface $customerSaver;

    public function __construct(CustomerSaverInterface $customerSaver)
    {
        $this->customerSaver = $customerSaver;
    }
    
    /**
     * 会員登録する
     *
     * @return \Illuminate\Http\Response
     */
    public function createCustomer(StoreCustomerRequest $request)
    {
        $customerData = new CustomerData(
            name: $request->name,
            kana: $request->kana,
            tel: $request->tel,
            email: $request->email,
            postcode: $request->postcode,
            address: $request->address,
            birthday: $request->birthday,
            gender: $request->gender,
            memo: $request->memo,
        );

        $customerResult = $this->customerSaver->saveCustomer($customerData);
        
        return response()->json($customerResult->customer);
    }


     /**
     * 顧客情報を変更、更新する 
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function updateCustomer(int $customerId,UpdateCustomerRequest $request)
    {
        $customerData = new CustomerData(
            name: $request->name,
            kana: $request->kana,
            tel: $request->tel,
            email: $request->email,
            postcode: $request->postcode,
            address: $request->address,
            birthday: $request->birthday,
            gender: $request->gender,
            memo: $request->memo,
        );

        $customerResult = $this->customerSaver->saveCustomer($customerData, $customerId);
        
        return response()->json($customerResult->customer);
    }


    /**
     * 登録を削除する
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($customerId)
    {
        $customer = Customer::query()->findOrFail($customerId);
        
        if ($customer) {
            $customer->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }

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

