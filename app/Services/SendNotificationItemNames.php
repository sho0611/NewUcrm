<?php

namespace App\Services;
use App\Models\Appointment;
use App\Models\Item;
use App\Models\Customer;
use App\Notifications\AppointmentCreated;



class SendNotificationItemNames
{
    public function sendNotificationItemNames(array $appointments, int $customerId, array $itemIds)
    {
        // アイテム名を取得
        $items = Item::whereIn('item_id', $itemIds)->get();
        $itemNames = $items->pluck('name')->implode(', ');

        // 顧客に通知
        $customer = Customer::where('customer_id', $customerId)->first();
        if ($customer) {
            $customer->notify(new AppointmentCreated(end($appointments), $itemNames));
        }
    }
}