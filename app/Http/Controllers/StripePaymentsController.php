<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Exception;
use App\Models\StripePayment;   

class StripePaymentsController extends Controller
{
   
    public function payment(Request $request, $appointmentId)  
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer = Customer::create([
                'email' => $request->stripeEmail,                
                'source' => $request->stripeToken
            ]);

            if (!$customer) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create customer'
                ]);
            }

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $request->amount,
                'currency' => 'jpy'
            ]);

            $payments = new StripePayment();
            $createPayments = [
                'appointment_id' => $appointmentId, 
                'charge_id' => $charge->id,
                'amount' => $request->amount,
                'customer_id' => $customer->id
            ]; 
            
            $payments->fill($createPayments);
            $payments->save();

            $payments->update([
                'appointment_id' => $request->appointment_id, 
            ]);
            
            

            return response()->json([
                'status' => 'success',
                'customer_id' => $customer->id, 
                'message' => 'Payment processed successfully',
                'charge_id' => $charge->id
            ]);

            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ]);
        }
    }
}


