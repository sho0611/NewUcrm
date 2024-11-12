<?php

namespace App\Services; 

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Exception;
use App\Services\storePaymentDetails; 

class StripePayments
{
    protected $storePaymentDetails;
    
    public function __construct(storePaymentDetails $storePaymentDetails)       
    {
        $this->storePaymentDetails = $storePaymentDetails;
    }
   
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

            $this->storePaymentDetails->createPaymentsTable($charge, $customer);    

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



