<?php

namespace App\Services;
use App\Models\Item;
use App\Models\Customer;
use App\Notifications\AppointmentCreated;
use App\Interfaces\SendNotificationItemNamesInterface;

class SendNotificationItemNames implements SendNotificationItemNamesInterface
{
    /**
     * 予約時にアイテム名を通知
     *
     * @param $appointment
     */
    public function sendNotificationItemNames($appointment)
    {
    
        $itemId = is_array($appointment->item_id) ? $appointment->item_id : [$appointment->item_id];
            $customerId = $appointment->customer_id; 
            $appointmentTime = $appointment->appointment_time; 
            $appointmentDate = $appointment->appointment_date;

            $itemNames = $this->getItemNamesByIds ($itemId);
            $customer = $this->getCustomerDetailByids($customerId);
                if ($customer) {
                    $customer->notify(new AppointmentCreated(end($appointment), $itemNames, $appointmentDate, $appointmentTime)); 
                }
    }

    /**
     * アイテムIDからアイテム名を取得
     *
     * @param $itemIds
     * @return $itemNames アイテム名をカンマ区切りで連結した文字列
     */
    private function getItemNamesByIds($itemId)
    {
        $items = Item::whereIn('item_id', $itemId)->first();
        $itemNames = $items->pluck('name')->implode(', ');
        return $itemNames;
    }

    /**
     * 顧客から顧客情報を取得
     *
     * @param $customerId
     *  @return Customer|null 顧客オブジェクトまたは null
     */
    private function getCustomerDetailByids(int $customerId): ?Customer
    {
        $customer = Customer::where('customer_id', $customerId)->first();
        return $customer;
    }
}
