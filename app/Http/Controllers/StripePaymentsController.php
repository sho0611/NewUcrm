<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Exception;
use App\Models\Appointment;



class StripePaymentsController extends Controller
{
    public function handlePayment(Request $request)
    {
        if ($request->payment_method === 'online')
        {
            return $this->payment($request);

        } elseif ($request->payment_method === 'onsite')

        {
            return $this->reserveWithoutPayment($request);   
        }
        
    }

    private function payment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer = Customer::create([
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ]);

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $request->amount,
                'currency' => 'jpy'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed successfully',
                'charge_id' => $charge->id
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   private function reserveWithoutPayment(Request $request)
   {
 
        $this->reservationStatus($request->reservation_id, 'unpaid');

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation created without payment'
        ]);
    }

    private function reservationStatus($appointmentId, $status)
    {
        // 予約の支払い状態を更新
        Appointment::where('appointment_id ', $appointmentId)->update(['payment_status' => $status]);
    }
}


