<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Exception;


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

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => 2000,
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

    public function complete()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Payment complete'
        ], 200);
    }
}


