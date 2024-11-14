<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\OrderItem;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;

use App\Models\PayPayPayments as Order; 
use Illuminate\Http\Request;

class PayPayController extends Controller
{
    public function payment(Request $request)
    {
        DB::beginTransaction();
        try {
            $isProduction = config('app.env') === 'production' ? true : false;

            $client = new Client([
                'API_KEY' => 'YOUR_API_KEY',
                'API_SECRET'=>'YOUR_API_SECRET',
                'MERCHANT_ID'=>'YOUR_MERCHANT_ID' 
                ],$isProduction);

            $orderName = 'テスト';                              
            $price = $request->price;                                 
            $items = (new OrderItem())->setName($orderName)
                                    ->setQuantity(1)
                                    ->setUnitPrice(['amount' => $price, 'currency' => 'JPY']);
            
            $paypayMerchantPaymentId = 'mpid_' . rand() . time();     
            $redirectUrl = route('paypay.complete');                    
    
            $CQPayload = new CreateQrCodePayload();
            $CQPayload->setOrderItems($items);
            $CQPayload->setMerchantPaymentId($paypayMerchantPaymentId);
            $CQPayload->setCodeType('ORDER_QR');
            $CQPayload->setAmount(['amount' => $price, 'currency' => 'JPY']);
            $CQPayload->setIsAuthorization(false);
            $CQPayload->setUserAgent($_SERVER['HTTP_USER_AGENT']);
            $CQPayload->setRedirectType('WEB_LINK');
            $CQPayload->setRedirectUrl($redirectUrl);
    
            $QRCodeResponse = $client->code->createQRCode($CQPayload);
    
            if ($QRCodeResponse['resultInfo']['code'] !== 'SUCCESS') {
                throw new \Exception('決済用QRコードが生成できませんでした');
            }
    
            Order::create([
                'price' => $price,
                'is_payment' => false,
                'paypay_merchant_payment_id' => $paypayMerchantPaymentId
            ]);
    
            DB::commit();
            return redirect()->to($QRCodeResponse['data']['url']); 

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
        }
    }

    public function webhook(Request $request)
{
    DB::beginTransaction();
    try {
        $state = $request->state;
        $paypayMerchantPaymentId = $request->merchant_order_id;

        if ($state === 'FAILED') {
            throw new \Exception('オーダーステータス: ' . $state);
        }

        $order = Order::where('paypay_merchant_payment_id', $paypayMerchantPaymentId)->first();
        if (empty($order)) {
            throw new \Exception('注文情報が存在しません');
        }

        $order->is_payment = true;
        $order->save();

        DB::commit();

        return response()->json(['message' => 'success'], 200);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error($e->getMessage());
    }
}
}


