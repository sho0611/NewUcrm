<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;


class GestItemController extends Controller
{ 
    /**
    *アイテム一覧を表示する
    *
    * @return \Illuminate\Http\Response
    */
   public function viewItems(Request $request)
   {
        $items = Item::query()
        ->select('*')
        ->get();

        return response()->json($items);
   }

}
