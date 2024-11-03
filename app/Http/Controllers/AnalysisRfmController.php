<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalysisRfmController extends Controller
{
    /**
     * RFMスコアを計算
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse RFMスコア
     */ 
    public function index(Request $request)
    {
        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');
    
        $subtotal = Order::betweenDate($startDate, $endDate);
        $totalPerPurchase = $this->getTotalPerPurchase($subtotal);
        $customerData = $this->getCustomerData($totalPerPurchase);
        $rfmData = $this->calculateRfm($customerData);
        $groupRfmByRecency = $this->groupRfmByRecency($rfmData);
    
        return response()->json($groupRfmByRecency);
    }

    /**
     * 購入IDごとの売上合計を取得
     *
     * @param $subQuery アイテムごとの売上
     * @return Collection 購入IDごとの売上
     */ 
    private function getTotalPerPurchase($subtotal)
    {
        return $subtotal
        ->groupBy('id')
            ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase, created_at')
            ->get(); 
    }

    /**
     * 顧客ごとの売上合計を取得
     *
     * @param $totalPerPurchase 購入IDごとの売上
     * @return Collection 顧客ごとの売上
     */ 
    private function getCustomerData($totalPerPurchase)
    {
        return $totalPerPurchase->groupBy('customer_id')->map(function($data){
            return [
                'customer_id' => $data->first()->customer_id,
                'customer_name' => $data->first()->customer_name,
                'recentData' => $data->max('created_at'),
                'recency' => Carbon::now()->diffInDays($data->max('created_at')),
                'frequency' => $data->count('customer_id'),
                'monetary' => $data->sum('totalPerPurchase'),
            ];
        });
    }

    /**
     * RFMスコアを計算
     *
     * @param $customerData 顧客ごとの売上データ
     * @return Collection RFMスコア
     */ 
    private function calculateRfm($customerData)
    {
        return $customerData->map(function ($data) {
            return [
                'customer_id' => $data['customer_id'],
                'customer_name' => $data['customer_name'],
                'recentDate' => $data['recentData'],
                'recency' => $data['recency'],
                'frequency' => $data['frequency'],
                'monetary' => $data['monetary'],
                'r' => $this->calculateRecencyScore($data['recency']),
                'f' => $this->calculateFrequencyScore($data['frequency']),
                'm' => $this->calculateMonetaryScore($data['monetary']),
            ];
        });
    }

    /**
     * Recencyでグループ化
     *
     * @param $rfmData RFMスコア
     * @return Collection Recencyでグループ化されたRFMスコア
     */ 
    private function groupRfmByRecency($rfmData)
    {
        return $rfmData->groupBy('r')->map(function($data){
                return [
                    'rRank' => 'r_' . $data[0]['r'],
                    'f_5' => $data->where('f', 5)->count(),
                    'f_4' => $data->where('f', 4)->count(),
                    'f_3' => $data->where('f', 3)->count(),
                    'f_2' => $data->where('f', 2)->count(),
                    'f_1' => $data->where('f', 1)->count(),

                    'm_5' => $data->where('m', 5)->count(),
                    'm_4' => $data->where('m', 4)->count(),
                    'm_3' => $data->where('m', 3)->count(),
                    'm_2' => $data->where('m', 2)->count(),
                    'm_1' => $data->where('m', 1)->count(),
                ];

        });
    }

    /**
     * Recencyスコアを計算
     *
     * @param $recency Recency
     * @return int Recencyスコア
     */ 
    private function calculateRecencyScore($recency)
    {
        if ($recency < 30) return 5;
        if ($recency < 60) return 4;
        if ($recency < 90) return 3;
        if ($recency < 120) return 2;   
        return 1;
    }

    /**
     * Frequencyスコアを計算
     *
     * @param $frequency Frequency
     * @return int Frequencyスコア
     */ 
    private function calculateFrequencyScore($frequency)
    {
        if ($frequency >= 10) return 5;
        if ($frequency >= 8) return 4;
        if ($frequency >= 6) return 3;
        if ($frequency >= 4) return 2;
        return 1;
    }

    /**
     * Monetaryスコアを計算
     *
     * @param $monetary Monetary
     * @return int Monetaryスコア
     */ 
    private function calculateMonetaryScore($monetary)
    {
        if ($monetary >= 100000) return 5;
        if ($monetary >= 70000) return 4;
        if ($monetary >= 50000) return 3;
        if ($monetary >= 30000) return 2;
        return 1;
    }
}

