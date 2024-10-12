<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AnalysisMouthController extends Controller
{
    public function index(Request $request)
    {

        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subquery = Order::betweenDate($startDate, $endDate)
        ->where('status', true)
        //id毎にグループ化
        ->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase,
        DATE_FORMAT(created_at, "%Y%m") as date');

    //2. サブクエリをgroupByで日毎にまとめる
    $data = DB::table($subquery)
        ->groupBy('date')
        ->selectRaw('date, SUM(totalPerPurchase) as total')
        ->get();
        
        return response()->json($data);
    }

}
