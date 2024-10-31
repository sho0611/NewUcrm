<?php

namespace App\Services;
use App\Models\Item;
use App\Models\Customer;
use App\Notifications\AppointmentCreated;
use App\Interfaces\SendNotificationItemNamesInterface;
use App\Notifications\changeSendNotification;

class SendNotificationItemNames implements SendNotificationItemNamesInterface
{
    /**
     * 予約作成時にアイテム名を含む予約内容を送信
     *
    * @param array $appointments 予約の配列
    * @param int $customerId 顧客のID
    * @param array $itemIds アイテムのIDの配列
    * @param string $firstAppointmentTime 最初の予約時間
     * @return void
     */
    public function sendNotificationItemNames(array $appointments, int $customerId, array $itemIds, string $firstAppointmentTime)
    {
       $itemNames = $this->getItemNamesByIds ($itemIds);
       $customer = $this->getCustomerId($customerId);
        if ($customer) {
            $customer->notify(new AppointmentCreated(end($appointments), $itemNames, $firstAppointmentTime));
        }
    }

    /**
     * 予約変更時にアイテム名を含む予約内容を送信
     *
     * @param array $appointments
     * @param integer $customerId
     * @param array $itemIds
     * @param string $firstAppointmentTime
     * @return void
     */
    public function changeSendNotification(array $appointments, int $customerId, array $itemIds, string $firstAppointmentTime)
    {
       
        $itemNames = $this->getItemNamesByIds ($itemIds);
        $customer = $this->getCustomerId($customerId);
        if ($customer) {
            $customer->notify(new changeSendNotification(end($appointments), $itemNames, $firstAppointmentTime));
        }
    }

    /**
     * アイテムIDからアイテム名を取得
     *
     * @param $itemIds
     * @return $itemNames アイテム名をカンマ区切りで連結した文字列
     */
    private function getItemNamesByIds($itemIds)
    {
        $items = Item::whereIn('item_id', $itemIds)->get();
        $itemNames = $items->pluck('name')->implode(', ');
        return $itemNames;
    }

    /**
     * 顧客から顧客情報を取得
     *
     * @param $customerId
     *  @return Customer|null 顧客オブジェクトまたは null
     */
    private function getCustomerId(int $customerId): ?Customer
    {
        $customer = Customer::where('customer_id', $customerId)->first();
        return $customer;
    }
}
