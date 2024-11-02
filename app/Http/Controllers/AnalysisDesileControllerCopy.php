<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;
;

class AnalysisDesileController extends Controller
{
    
    public function index(Request $request)
    {
        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subQuery = Order::betweenDate($startDate, $endDate);
        //OK
        $totalSalesPerPurchase = $this->getTotalSalesPerPurchase($subQuery);
        
        $salesTotalByCustomer = $this->getSalesTotalByCustomer($totalSalesPerPurchase);
        return response()->json($salesTotalByCustomer);
        $salesWithRowNum = $this->addRowNumbers($salesTotalByCustomer);
        
        $divideIntoDeciles = $this->createdivideIntoDeciles($salesWithRowNum); 
    
        $avarageTotalPerGroup = $this->createAvarageTotalPerGroup($divideIntoDeciles);
        $total = $salesWithRowNum->sum('total');

        $data = $this->calculateTotalRatios($avarageTotalPerGroup, $total);

        return response()->json($data);
    }

    private function getTotalSalesPerPurchase($subQuery)
    {
        return $subQuery
        ->groupBy('id')
        ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase')
        ->get();        
    }

    private function getSalesTotalByCustomer($totalSalesPerPurchase)
    {
        return $totalSalesPerPurchase
        ->groupBy('customer_id')
        ->selectRaw('customer_id, customer_name, sum(totalPerPurchase) as total')
        ->orderBy('total', 'desc')
        ->get();
    }

    private function addRowNumbers($salesTotalByCustomer)
    {
        $rownum = 0; 
        return $salesTotalByCustomer->map(function($item) use (&$rownum) {
            $item->row_num = ++$rownum; 
            return $item;
        });
    }
    
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
                if ($item->row_num >= $bindValues[$i] && $item->row_num <= $bindValues[$i + 1]) {
                    $item->decile = ($i / 2) + 1;
                    break;
                }
            }
            return $item;
        });
    }
    
    private function createAvarageTotalPerGroup($divideIntoDeciles)
    {
        return $divideIntoDeciles
        ->groupBy('decile')
        ->selectRaw('decile, round(avg(total)) as average, sum(total) as totalPerGroup');
    }

    private function calculateTotalRatios($averageTotalPerGroup, $total)
    {
        return $averageTotalPerGroup->map(function($item) use ($total) {
            $item->totalRatio = round(100 * ($item->totalPerGroup ?? 0) / $total, 1);
            return $item;
        });
    }
}
