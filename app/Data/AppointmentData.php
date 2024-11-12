<?php

namespace App\Data;

class AppointmentData
{
    public array $itemIds;
    public int $customerId;
    public int $staffId;
    public string $appointmentDate;
    public string $appointmentTime;
    public string $paymentMethod;
    public string $status;  
    /**
     * AppointmentData クラスのコンストラクタ
     * 
     * @param array $itemIds アイテムのIDの配列
     * @param int $customerId 顧客のID
     * @param int $staffId スタッフのID
     * @param string $appointmentDate 予約日（YYYY-MM-DD形式）
     * @param string $appointmentTime 予約時間（HH:MM形式）
     * @param string $paymentMethod 支払い方法  
     * 
     */
    public function __construct(array $itemIds, int $customerId, int $staffId, string $appointmentDate , string $appointmentTime, string $paymentMethod, string $status)               
    {
        $this->itemIds = $itemIds;
        $this->customerId = $customerId;
        $this->staffId = $staffId;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime; 
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;         
    }
}
