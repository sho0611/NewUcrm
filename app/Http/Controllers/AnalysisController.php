<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
{
    $subQuery = Purchase::query()
        ->leftJoin('item_purchase', 'purchases.id', '=', 'item_purchase.purchase_id')
        ->leftJoin('items', 'item_purchase.item_id', '=', 'items.id')
        ->selectRaw('purchases.id, SUM(items.price * item_purchase.quantity) as subtotal, purchases.created_at')
        ->groupBy('purchases.id');

    // 販売しているもののみ取得
    $query = $subQuery
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase, DATE_FORMAT(created_at, "%Y%m%d") as date');

    // 結果を取得
    $results = $query->get();

    return response()->json($results);
}

}
