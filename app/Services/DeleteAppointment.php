<?php

namespace App\Services;
use App\Models\Appointment; 
use Stripe\Stripe;  
use Stripe\Refund;  
use App\Models\StripePayment;   
use Exception;  

class DeleteAppointment
{
    /**
     * 予約の削除
     *
     * @param integer $appId
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function deleteAppointment($appId)
    {
        $appointment = Appointment::query()->find($appId); 
        if ($appointment) {
            $payment = StripePayment::query()->where('appointment_id', $appId)->first(); 

            try {
                if ($payment) {
                    $this->processRefund($payment);   
                }

                $appointment->delete();

                return response()->json(['message' => 'Deleted successfully',
            'refund_id' => $payment ? $payment->charge_id : null]); 
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Refund failed', 
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }

    /**
     * 返金処理
     *
     * @param StripePayment $payment
     * @return void
     */ 
    private function processRefund($payment)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $refund = Refund::create([
            'charge' => $payment->charge_id,
        ]); 
    }
}


