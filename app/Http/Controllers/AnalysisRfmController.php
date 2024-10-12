<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisRfmController extends Controller
{


       
    public function index(Request $request)
    {
        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');
    
        $subQuery = Order::betweenDate($startDate, $endDate)
            ->groupBy('id')
            ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase, MAX(created_at) as created_at')
            ->get(); 
    
        $customerData = $subQuery->groupBy('customer_id')->map(function ($group) {
            return [
                'customer_id' => $group[0]->customer_id,
                'customer_name' => $group[0]->customer_name,
                'recentDate' => $group->max('created_at'),
                'recency' => now()->diffInDays($group->max('created_at')),
                'frequency' => $group->count(),
                'monetary' => $group->sum('totalPerPurchase'),
            ];
        })->values(); 
    
        $finalData = $customerData->map(function ($item) {
            return [
                'customer_id' => $item['customer_id'],
                'customer_name' => $item['customer_name'],
                'recentDate' => $item['recentDate'],
                'recency' => $item['recency'],
                'frequency' => $item['frequency'],
                'monetary' => $item['monetary'],
                'r' => $item['recency'] < 14 ? 5 :
                        ($item['recency'] < 28 ? 4 :
                        ($item['recency'] < 60 ? 3 :
                        ($item['recency'] < 90 ? 2 : 1))),


                'f' => $item['frequency'] >= 7 ? 5 :
                        ($item['frequency'] >= 5 ? 4 :
                        ($item['frequency'] >= 3 ? 3 :
                        ($item['frequency'] >= 2 ? 2 : 1))),


                'm' => $item['monetary'] >= 300000 ? 5 :
                        ($item['monetary'] >= 200000 ? 4 :
                        ($item['monetary'] >= 100000 ? 3 :
                        ($item['monetary'] >= 30000 ? 2 : 1))),
            ];
        });
    
        $rCount = $finalData->groupBy('r')->map->count();
        $fCount = $finalData->groupBy('f')->map->count();
        $mCount = $finalData->groupBy('m')->map->count();
    
        $data = $finalData->groupBy('r')->map(function ($group) {
            return [
                'rRank' => 'r_' . $group[0]['r'],
                'f_5' => $group->where('f', 5)->count(),
                'f_4' => $group->where('f', 4)->count(),
                'f_3' => $group->where('f', 3)->count(),
                'f_2' => $group->where('f', 2)->count(),
                'f_1' => $group->where('f', 1)->count(),
            ];
        })->values()->sortByDesc('rRank'); 
    
        return response()->json($data);
    }
    
        
    }

