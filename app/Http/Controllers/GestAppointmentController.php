<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Data\AppointmentData;
use App\Interfaces\AppointmentSaverInterface;
use Illuminate\Support\Facades\Log;
use App\Services\CreateTimeArray;
use App\Services\GetReservedTimeArray;



class GestAppointmentController extends Controller
{

    protected $appointmentSaver;
    protected $getReservedTimeArray;
    protected $createTimeArray;
    
    public function __construct(AppointmentSaverInterface $appointmentSaver, GetReservedTimeArray $getReservedTimeArray,CreateTimeArray $createTimeArray)
    {
        $this->appointmentSaver = $appointmentSaver; 
        $this->getReservedTimeArray = $getReservedTimeArray;
        $this->createTimeArray = $createTimeArray; 
    }
     /**
     * 予約の作成
     *
     * @param StoreAppointmentRequest $request
     * @return \Illuminate\Http\JsonResponse 予約結果をJSONで返却
     */
    public function createAppointment(StoreAppointmentRequest $request)
    {
        $appointmentData = new AppointmentData(
            itemIds: $request->item_id,
            customerId: $request->customer_id,
            staffId: $request->staff_id,
            appointmentDate: $request->appointment_date,
            appointmentTime: $request->appointment_time
        );
        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData);

        return response()->json($appointmentResult->appointments);
    }

    /**
     * 予約可能な時間を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTimes(Request $request)
    {
    
    $appointDate = $request->query('date');
    $times = $this->createTimeArray->createTimeArray();
    $reservedTimes = $this->getReservedTimeArray->getReservedTime($appointDate);

    $availableTimes = array_diff($times, $reservedTimes);
    
        return response()->json([
            'availableTimes' => array_values($availableTimes),
        ]); 
    }


    /**
     * 予約の変更
     *
     * @param integer $appId
     * @param UpdateAppointmentRequest $request
     * @return void
     */
    public function changAppointment(int $appId, UpdateAppointmentRequest $request)
    {
        $appointmentData = new AppointmentData(
            itemIds: $request->item_id,
            customerId: $request->customer_id,
            staffId: $request->staff_id,
            appointmentDate: $request->appointment_date,
            appointmentTime: $request->appointment_time
        );
        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData, $appId);

        return response()->json($appointmentResult->appointments);
    }

    /**
     * 予約の削除
     *
     * @param $appId
     * @return void
     */
    public function deleteAppointment($appId)
    {
        $appointment = Appointment::query()->findOrFail($appId);
        if ($appointment) {
            $appointment->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
}
