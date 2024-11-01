<?php

namespace App\Services;

use App\Models\Customer;
use App\Data\CustomerData;
use App\Data\CustomerResult;
use App\Interfaces\CustomerSaverInterface;
use App\Services\sendCouponToCustomer;

class SaveCustomer implements CustomerSaverInterface
{
    protected sendCouponToCustomer $sendCouponToCustomer;

    public function __construct(sendCouponToCustomer $sendCouponToCustomer)
    {
        $this->sendCouponToCustomer = $sendCouponToCustomer;
    }
    
    /**
     * 顧客情報を保存する
     * 新規登録の場合はクーポンを送信する
     *
     * @param CustomerData $customerData
     * @param integer|null $customerId
     * @return CustomerResult
     */
    public function saveCustomer(CustomerData $customerData, ?int $customerId = null): CustomerResult
    {
        if ($customerId) {
            $customer = Customer::findOrFail($customerId);
            if (!$customer) {
                return response()->json(['error' => 'Customer not found for ID: ' . $customerId]);
            }
        } else {
            $customer = new Customer();
        }

        $customerCreateArray = [
            'name' => $customerData->name,
            'kana' => $customerData->kana,
            'tel' => $customerData->tel,
            'email' => $customerData->email,
            'postcode' => $customerData->postcode,
            'address' => $customerData->address,
            'birthday' => $customerData->birthday,
            'gender' => $customerData->gender,
            'memo' => $customerData->memo,
        ];

        $customer->fill($customerCreateArray);
        $customer->save();

        if(!$customerId)
        {
            $this->sendCouponToCustomer->sendCouponToCustomer($customer);
        }

        return new CustomerResult($customer);
    }
}





