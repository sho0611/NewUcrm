<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Item::query()
        ->select('*')
        ->get();
        return response()->json($items); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {
       


    // 保存したデータをJSONで返す
    return response()->json($request); 
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */
/**
 * Store a newly created resource in storage.
 *
 * @param  \App\Http\Requests\StoreItemRequest  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    // フィールドのバリデーションが行われている前提

    $item = new Item();

    $itemCreateArray = [
        'name' => $request->name,
        'memo' => $request->memo,
        'price' => $request->price,
        'is_selling' => $request->is_selling
    ];

    
    $item->fill($itemCreateArray);
    $item->save();

  
    return response()->json($item);
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return response()->json($item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(int $itemsId, Request $request)
    {
        $item = Item::query()->findOrFail($itemsId);

        $itemupdateArry = [
            'name' => $request->name,
            'memo' => $request->memo,
            'price' => $request->price,
            'is_selling' => $request->is_selling
        ];
        $item->fill($itemupdateArry)->save();
        return response()->json($item); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json($item);
    }
}
