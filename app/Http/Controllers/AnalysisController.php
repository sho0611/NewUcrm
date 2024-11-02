<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    /**
     * 日べつの売り上げデータを取得する
     *
     * @param Request $request
     * @return void
     */
    public function analysisDay(Request $request)
    {
        return $this->generateAnalysisData($request, "%Y%m%d");
    }

   /**
     * 月べつの売り上げデータを取得する
     *
     * @param Request $request
     * @return void
     */
    public function analysisMouth(Request $request)
    {
        return $this->generateAnalysisData($request, "%Y%m");
    }

     /**
     * 年べつの売り上げデータを取得する
     *
     * @param Request $request
     * @return void
     */
    public function analysisYear(Request $request)
    {
        return $this->generateAnalysisData($request, "%Y");
    }

    /**
     * 売り上げデータを生成する
     * 
     * @param Request $request
     * @param string $format 年/月/日
     * @return \Illuminate\Http\JsonResponse 売上データの JSON レスポンス
     */
    private function generateAnalysisData(Request $request, string $format)
    {
        $startDate = $request->query('startDate'); 
        $endDate = $request->query('endDate');

        $subQuery = Order::betweenDate($startDate, $endDate)
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase, DATE_FORMAT(created_at, ?)as date', [$format]);

        $data = DB::table($subQuery)
        ->groupBy('date')
        ->selectRaw('date, SUM(totalPerPurchase) as total')
        ->get();

        return response()->json($data);
    }
}