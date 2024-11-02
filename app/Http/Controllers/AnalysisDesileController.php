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
    public function index(Request $request)
    {
        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subQuery = Order::betweenDate($startDate, $endDate);
    
        $totalSalesPerPurchase = $this->getTotalSalesPerPurchase($subQuery);
        
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
        return $totalSalesPerPurchase->groupBy('customer_id')->map(function($items) {
            return [
                'customer_id' => $items->first()->customer_id,
                'customer_name' => $items->first()->customer_name,
                'total' => $items->sum('totalPerPurchase'),
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
        return $salesTotalByCustomer->map(function($item) use (&$rownum) {
            $item['row_num'] = ++$rownum; 
            return $item;
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
        $total = $salesWithRowNum->sum('total');
    
        $decileSize = ceil($count / 10); 
        $bindValues = [];
        $tempValue = 0;
    
        for ($i = 1; $i <= 10; $i++) {
            array_push($bindValues, $tempValue + 1);
            $tempValue += $decileSize;
            array_push($bindValues, min($tempValue, $count)); 
        }

        return $salesWithRowNum->map(function($item) use ($bindValues) {
            for ($i = 0; $i < count($bindValues); $i += 2) {
                if ($item['row_num'] >= $bindValues[$i] && $item['row_num'] <= $bindValues[$i + 1]) {
                    $item['decile'] = ($i / 2) + 1;
                    break;
                }
            }
            return $item;
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
        return $divideIntoDeciles->groupBy('decile')->map(function($items){
            return [
                'decile' => $items->first()['decile'],
                'average' => round($items->avg('total'),1),
                'totalPerGroup' => $items->sum('total')
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
        return $averageTotalPerGroup->map(function($item) use ($total) {
            $item['totalRatio'] = round(100 * ($item['totalPerGroup']) / $total, 1);
            return $item;
        });
    }
}
