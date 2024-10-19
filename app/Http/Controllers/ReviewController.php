<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewForm(Request $request)
    {
        //アイテムの情報を渡す
        $reviews = Review::with(['item'])->get();
        return response()->json($reviews);
    }

       /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReviewRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function createReview(StoreReviewRequest $request)
    {

        $reviews = new Review();

        $reviewCreateArray = [
            'item_id'=> $request->item_id,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment
        ];

        $reviews->fill($reviewCreateArray);
        $reviews->save();

 
        return response()->json($reviews);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewReviews()
    {
        $reviews = Review::with(['item'])
        ->get();

        return response()->json($reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewItemReviews(int $itemId, Request $request)
    {
        $reviews = Review::with(['item'])
        ->where('item_id', $itemId)
        ->get();

        return response()->json($reviews);
    }
}
