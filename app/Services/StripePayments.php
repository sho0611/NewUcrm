<?php

namespace App\Services; 

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Exception;
use App\Services\storePaymentDetails; 
use App\Models\Item;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

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

            $totalAmount = 0;
            foreach ($request->item_id as $itemId) {
                    $item = Item::findOrFail($itemId); 
                    $totalAmount += $item->price;
            }

            if(!$totalAmount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items in cart'
                ]);
            }   
            
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
                'amount' => $totalAmount,  
                'currency' => 'jpy'
            ]);

            $this->storePaymentDetails->createPaymentsTable($charge, $customer);    

            return response()->json([
                'status' => 'success',
                'customer_id' => $customer->id, 
                'paid' => $totalAmount,   
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

