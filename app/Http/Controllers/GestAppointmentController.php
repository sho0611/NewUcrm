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
use App\Services\DeleteAppointment;
use App\Services\storePaymentDetails;



class GestAppointmentController extends Controller
{

    protected $appointmentSaver;
    protected $getReservedTimeArray;
    protected $createTimeArray;
    protected $stripePaymentsController;
    protected $deleteAppointment;
    protected $storePaymentDetails; 
    
    public function __construct(AppointmentSaverInterface $appointmentSaver, GetReservedTimeArray $getReservedTimeArray,CreateTimeArray $createTimeArray, StripePaymentsController $stripePaymentsController, DeleteAppointment $deleteAppointment,)    
    {
        $this->appointmentSaver = $appointmentSaver; 
        $this->getReservedTimeArray = $getReservedTimeArray;
        $this->createTimeArray = $createTimeArray; 
        $this->stripePaymentsController = $stripePaymentsController;
        $this->deleteAppointment = $deleteAppointment; 
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
            appointmentTime: $request->appointment_time,
            paymentMethod: $request->payment_method
        );

        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData);

        return response()->json([
            'status' => 'temporary_reservation',
            'appointment' => $appointmentResult->appointments
        ]);    
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
            appointmentTime: $request->appointment_time,
            paymentMethod: $request->payment_method, 
        );
        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData, $appId);

        return response()->json($appointmentResult->appointments);
    }

    /**
     * 予約の削除
     *
     * @param integer $appId
     * @return void
     */ 
    public function deleteAppointment(int $appId)
    {
        $deleteAppointment = $this->deleteAppointment->deleteAppointment($appId);
        return response()->json($deleteAppointment);
    }
}
