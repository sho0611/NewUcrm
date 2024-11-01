<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{

        public function analysisDay(Request $request)
    {

        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subquery = Order::betweenDate($startDate, $endDate)
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase,
        DATE_FORMAT(created_at, "%Y%m%d") as date');

    $data = DB::table($subquery)
        ->groupBy('date')
        ->selectRaw('date, SUM(totalPerPurchase) as total')
        ->get();

        return response()->json($data);
    }

    public function analysisMonth(Request $request)
    {

        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subquery = Order::betweenDate($startDate, $endDate)
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase,
        DATE_FORMAT(created_at, "%Y%m") as date');

    $data = DB::table($subquery)
        ->groupBy('date')
        ->selectRaw('date, SUM(totalPerPurchase) as total')
        ->get();
        
        return response()->json($data);
    }

    public function analysisYear(Request $request)
    {

        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subquery = Order::betweenDate($startDate, $endDate)
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase,
        DATE_FORMAT(created_at, "%Y") as date');
        
        $data = DB::table($subquery)
        ->groupBy('date')
        ->selectRaw('date, SUM(totalPerPurchase) as total')
        ->get();
                
        return response()->json($data);
    }

}
