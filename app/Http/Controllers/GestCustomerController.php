<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Interfaces\CustomerSaverInterface;
use App\Data\CustomerData;



class GestCustomerController extends Controller
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
}    

