<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AnalysisDesileController extends Controller
{
    /**
     * デシル分析を実行
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse デシル分析結果
     */
    public function desile(Request $request)
    {
        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subtotal = Order::betweenDate($startDate, $endDate);
    
        $totalSalesPerPurchase = $this->getTotalSalesPerPurchase($subtotal);

        $salesTotalByCustomer = $this->getSalesTotalByCustomer($totalSalesPerPurchase);
        
        $salesWithRowNum = $this->addRowNumbers($salesTotalByCustomer);

        $divideIntoDeciles = $this->createdivideIntoDeciles($salesWithRowNum);

        $avarageTotalPerGroup = $this->createAvarageTotalPerGroup($divideIntoDeciles);

        $total = $salesWithRowNum->sum('total');
        $data = $this->calculateTotalRatios($avarageTotalPerGroup, $total);

        return response()->json($data);
    }

    /**
     * 購入IDごとの売上合計を取得
     *
     * @param $subQuery アイテムごとの売上
     * @return Collection 購入IDごとの売上
     */
    private function getTotalSalesPerPurchase($subQuery)
    {
        return $subQuery
        ->groupBy('id')
        ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase')
        ->get();        
    }

    /**
     * 顧客ごとの売上合計を取得
     *
     * @param $totalSalesPerPurchase 購入IDごとの売上
     * @return Collection 顧客ごとの売上
     */
    private function getSalesTotalByCustomer($totalSalesPerPurchase)
    {
        return $totalSalesPerPurchase->groupBy('customer_id')->map(function($data) {
            return [
                'customer_id' => $data->first()->customer_id,
                'customer_name' => $data->first()->customer_name,
                'total' => $data->sum('totalPerPurchase'),
            ];
            
        })->sortByDesc('total')->values();
    }

    /**
     * 行番号を追加
     *
     * @param $salesTotalByCustomer 顧客ごとの売上
     * @return Collection 行番号が追加された売上
     */
    private function addRowNumbers($salesTotalByCustomer)
    {
        $rownum = 0; 
        return $salesTotalByCustomer->map(function($data) use (&$rownum) {
            $data['row_num'] = ++$rownum; 
            return $data;
        });
    }
    
    /**
     * 売上を10分位に分割
     *
     * @param $salesWithRowNum 行番号が追加された売上
     * @return Collection 10分位に分割された売上
     */
    private function createDivideIntoDeciles($salesWithRowNum)
    {
        $count = $salesWithRowNum->count();
    
        $decileSize = ceil($count / 10); 
        $bindValues = [];
        $tempValue = 0;
    
        for ($i = 1; $i <= 10; $i++) {
            array_push($bindValues, $tempValue + 1);
            $tempValue += $decileSize;
            array_push($bindValues, min($tempValue, $count)); 
        }

        return $salesWithRowNum->map(function($data) use ($bindValues) {
            for ($i = 0; $i < count($bindValues); $i += 2) {
                if ($data['row_num'] >= $bindValues[$i] && $data['row_num'] <= $bindValues[$i + 1]) {
                    $data['decile'] = ($i / 2) + 1;
                    break;
                }
            }
            return $data;
        });
    }

    /**
     * 10分位ごとの平均売上を取得
     *
     * @param $divideIntoDeciles 10分位に分割された売上
     * @return Collection 10分位ごとの平均売上
     */
    private function createAvarageTotalPerGroup($divideIntoDeciles)
    {
        return $divideIntoDeciles->groupBy('decile')->map(function($data){
            return [
                'decile' => $data->first()['decile'],
                'average' => round($data->avg('total'),1),
                'totalPerGroup' => $data->sum('total')
            ];

        });
    }

    /**
     * 売上合計から割合を計算
     *
     * @param $averageTotalPerGroup 10分位ごとの平均売上
     * @param $total 売上合計
     * @return Collection 10分位ごとの割合が追加された売上
     */
    private function calculateTotalRatios($averageTotalPerGroup, $total)
    {
        return $averageTotalPerGroup->map(function($data) use ($total) {
            $data['totalRatio'] = round(100 * ($data['totalPerGroup']) / $total, 1);
            return $data;
        });
    }
}
