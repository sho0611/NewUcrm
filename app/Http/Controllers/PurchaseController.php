<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Interfaces\PurchaseSaverInterface;
use App\Data\PurchaseData;


class PurchaseController extends Controller
{
    protected PurchaseSaverInterface $purchaseSaver;

    public function __construct(PurchaseSaverInterface $purchaseSaver)
    {
        $this->purchaseSaver = $purchaseSaver;
    }
    
    /**
     * 購入情報を作成する
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createPurchase(StorePurchaseRequest $request)
    {
        $purchaseData = new PurchaseData(
            customer_id: $request->customer_id,
            status: $request->status,
            items: $request->items
        );
    
        $purchaseResult = $this->purchaseSaver->savePurchase($purchaseData);
        return response()->json($purchaseResult->purchase);
    }

      /**
     * 購入情報を変更、更新する
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function updatePurchase(int $purchaseId, UpdatePurchaseRequest $request)
    {
        $purchaseData = new PurchaseData(
            customer_id: $request->customer_id,
            status: $request->status,
            items: $request->items
        );
    
        $purchaseResult = $this->purchaseSaver->savePurchase($purchaseData,$purchaseId);

        return response()->json($purchaseResult->purchase);
    }


       /**
     * 購入情報を取得し、アイテムと顧客情報を取得する
     *
     * @return \Illuminate\Http\Response
     */
    public function viewPurchase(Request $request)
    {
        $orders = Purchase::getPurchaseWithDetails();
        return response()->json($orders);
    }


  

    /**
     * 購入情報を削除する
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroyPurchas($purchase)
    {
        $purchase->delete();
        return response()->json($purchase);
    }
}
