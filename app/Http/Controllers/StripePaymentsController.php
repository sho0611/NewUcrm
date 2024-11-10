<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Exception;
use Illuminate\Support\Facades\Log; 

class StripePaymentsController extends Controller
{
   
    public function payment(Request $request)
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

            if (!$charge) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not charge.'
                ]);
            }

            return response()->json([
                'status' => 'success',
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


