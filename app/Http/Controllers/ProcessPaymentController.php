<?php

namespace App\Http\Controllers;

use App\Models\Appointment; 
use Illuminate\Http\Request;    
use App\Services\storePaymentDetails; 
use App\Services\StripePayments;

class ProcessPaymentController extends Controller
{
    protected $storePaymentDetails;
    protected $StripePayments;
    
    public function __construct(StripePayments $StripePayments, storePaymentDetails $storePaymentDetails)       
    {
        $this->storePaymentDetails = $storePaymentDetails;
        $this->StripePayments = $StripePayments;    
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
        $paymentResult = $this->StripePayments->payment($request);
    
        $paymentResultData = $paymentResult->getData();
    
        if ($paymentResultData->status === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed'
            ]);
        }
    
        $appointmentId = $request->appointment_id;
        $appointment = Appointment::find($appointmentId);
    
        $appointment->status = 'reserved';
        $appointment->payment_method = 'paid'; 
        $appointment->save();
        
        $this->storePaymentDetails->upDatePaymentstable($appointmentId);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Reservation confirmed and paid',
            'appointment' => $appointment
        ]);
    }
    
}
