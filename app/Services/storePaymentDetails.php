<?php

namespace App\Services;

use App\Models\StripePayment;   

class StorePaymentDetails
{
    public function createPaymentsTable($charge, $customer)
    {
        $payments = new StripePayment();
        $createPayments = [
            'appointment_id' => null, 
            'charge_id' => $charge->id,
            'amount' => $charge->amount,    
            'customer_id' => $customer->id
        ];

        $payments->fill($createPayments);
        $payments->save();
    
        return response()->json($payments);
    }

    public function upDatePaymentsTable($appointmentId)
    {
        $payment = StripePayment::where('appointment_id', null)->first(); 

        if ($payment) {
            $payment->appointment_id = $appointmentId;
            $payment->save();  
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment record not found'
            ]);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Payment updated successfully'
        ]);
    } 
}


