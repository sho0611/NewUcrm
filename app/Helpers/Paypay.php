<?php
namespace App\Helpers;

use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;
use Illuminate\Support\Facades\Config; 
use Illuminate\Support\Facades\Log;


class PayPay {
  protected Client $paypayClient;

  function __construct() {
    $paypayConfig = Config::get('paypay');

    $this->paypayClient = new Client([
      'API_KEY' => $paypayConfig['API_KEY'],
      'API_SECRET' => $paypayConfig['API_SECRET'],
      'MERCHANT_ID' => $paypayConfig['MERCHANT_ID'],
    ], true); //Set True for Production Environment. By Default this is set False for Sandbox Environment.


    // Creating the payload to create a QR Code, additional parameters can be added basis the API Documentation
    $payload = new CreateQrCodePayload();
    $payload->setMerchantPaymentId("my_payment_id" . \time());
    $payload->setCodeType("ORDER_QR");
    $amount = [
        "amount" => 1,
        "currency" => "JPY"
    ];
    $payload->setAmount($amount);
    $payload->setRedirectType('WEB_LINK');
    $payload->setRedirectUrl('https://paypay.ne.jp/');
    $payload->setUserAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1');
    
    //=================================================================
    // Calling the method to create a qr code
    //=================================================================
    $response = $this->paypayClient->code->createQRCode($payload);
    // 処理がうまくいってなかったら抜ける
    if($response['resultInfo']['code'] !== 'SUCCESS') {
      return;
    }

    // Collectionに変換しておく
    $QRCodeResponse = collect($response['data']);

    //=================================================================
    // Calling the method to get payment details
    //=================================================================
    $response = $this->paypayClient->payment->getPaymentDetails($QRCodeResponse['merchantPaymentId']);
    // 処理がうまくいってなかったら抜ける
    if($response['resultInfo']['code'] !== 'SUCCESS') {
      return;
    }

    // Collectionに変換しておく
    $QRCodeDetails = collect($response['data']);

    //=================================================================
    // Calling the method to cancel a Payment
    //=================================================================
    $response = $this->paypayClient->payment->cancelPayment($QRCodeResponse['merchantPaymentId']);
    // 処理がうまくいってなかったら抜ける
    if($response['resultInfo']['code'] !== 'REQUEST_ACCEPTED') {
      return;
    }

    Log::info(print_r($QRCodeResponse, true));
    Log::info(print_r($QRCodeDetails, true));
    Log::info(print_r($response, true));
  }

  function __destruct() {
  }
}
