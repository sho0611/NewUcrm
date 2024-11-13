<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripePayments;
use App\Services\StorePaymentDetails;
use App\Models\Appointment;
use App\Services\SendNotificationItemNames; 

class ProcessPaymentController extends Controller
{
    protected $storePaymentDetails;
    protected $stripePayments;
    protected $sendNotificationItemNames;   
    
    public function __construct(StripePayments $stripePayments, StorePaymentDetails $storePaymentDetails, SendNotificationItemNames $sendNotificationItemNames)         
    {
        $this->storePaymentDetails = $storePaymentDetails;
        $this->stripePayments = $stripePayments;  
        $this->sendNotificationItemNames = $sendNotificationItemNames;   
    }

    /**
     * 予約の支払い処理
     * 支払い完了後に予約ステータスを変更
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        $paymentResult = $this->stripePayments->payment($request);
        $paymentResultData = $paymentResult->getData();
    
        if ($paymentResultData->status === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed'
            ]);
        }
    
        $appointmentIds = $request->appointment_id; 
        foreach ($appointmentIds as $appointmentId) {
            $appointment = Appointment::find($appointmentId);
        
            if (!$appointment) {
                continue;
            }
        
            $appointment->status = 'reserved';
            $appointment->payment_method = 'paid'; 
            $appointment->save();
        
            $this->storePaymentDetails->upDatePaymentstable($appointmentId);
            $this->sendNotificationItemNames->sendNotificationItemNames($appointment); 
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation confirmed and paid',
            'appointment' => $appointment
        ]);
    }
}
