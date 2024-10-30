<?php

namespace App\Data;

class AppointmentData
{
    public array $itemIds;
    public int $customerId;
    public int $staffId;
    public string $appointmentDate;
    public string $appointmentTime;
    /**
     * AppointmentData クラスのコンストラクタ
     * 
     * @param array $itemIds アイテムのIDの配列
     * @param int $customerId 顧客のID
     * @param int $staffId スタッフのID
     * @param string $appointmentDate 予約日（YYYY-MM-DD形式）
     * @param string $appointmentTime 予約時間（HH:MM形式）
     */
    public function __construct(array $itemIds, int $customerId, int $staffId, string $appointmentDate, string $appointmentTime)
    {
        $this->itemIds = $itemIds;
        $this->customerId = $customerId;
        $this->staffId = $staffId;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
    }
}
