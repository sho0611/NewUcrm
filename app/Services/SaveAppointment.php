<?php

namespace App\Services;

use App\Models\Appointment;
use App\Data\AppointmentData;
use App\Data\AppointmentResult;
use App\Services\sendNotificationItemNames;
use App\Interfaces\AppointmentSaverInterface;

class SaveAppointment implements AppointmentSaverInterface
{
    private $firstAppointmentTime;

    /**
     * 予約を保存する 
     * idがあれば更新、なければ新規作成
     * 予約時予約内容の通知を送信
     * 予約変更時に予約内容の通知を送信
     *
     * @param AppointmentData $appointmentData
     * @return AppointmentResult
     */
    public function saveAppointments(AppointmentData $appointmentData, ?int $appId = null): AppointmentResult
    {
        $appointments = [];
        foreach ($appointmentData->itemIds as $index => $itemId) {
            if ($appId) {
                $appointment = Appointment::findOrFail($appId);
                if (!$appointment)
                {
                    return response()->json(['error' => 'Appointment not found for ID: ' . $appId]);
                }
            } else {
                $appointment = new Appointment(); 
            }

            $createAppointments = [
                'item_id' => $itemId,
                'customer_id' => $appointmentData->customerId,
                'staff_id' => $appointmentData->staffId,
                'appointment_date' => $appointmentData->appointmentDate,
                'appointment_time' => $this->calculateAppointmentTime($appointmentData,
                $appointments, $index), 
                'payment_method' => $appointmentData->paymentMethod,
                'status' => $appointmentData->status        
            ];
            $appointment->fill($createAppointments);
            $appointment->save();
            $appointments[] = $appointment;
        }
        return new AppointmentResult($appointments);
    }
    /**
     * 複数の予約時に次の予約の時間を計算する
     *
     * @param $appointmentData 予約データを含むオブジェクト
     * @param $appointments これまでに作成された予約オブジェクトの配列
     * @param $index 現在の予約が配列内で何番目であるかのインデックス
     * @return string 次の予約の時間
     */
    private function calculateAppointmentTime($appointmentData, $appointments, $index)
    {
        if ($index === 0) {
            $this->firstAppointmentTime = $appointmentData->appointmentTime;
            return $this->firstAppointmentTime;
        } else {
            $previousAppointment = $appointments[$index - 1];
            $previousDuration = $previousAppointment->item->duration; 
            $newAppointmentTime = new \DateTime($appointmentData->appointmentTime);
            $newAppointmentTime->add(new \DateInterval("PT{$previousDuration}M")); 
            return $newAppointmentTime->format('H:i');    
        }
    }
}




