<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripePayments;
use App\Services\StorePaymentDetails;
use App\Models\Appointment;

class ProcessPaymentController extends Controller
{
    protected $storePaymentDetails;
    protected $stripePayments;
    
    public function __construct(StripePayments $stripePayments, StorePaymentDetails $storePaymentDetails)       
    {
        $this->storePaymentDetails = $storePaymentDetails;
        $this->stripePayments = $stripePayments;    
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
