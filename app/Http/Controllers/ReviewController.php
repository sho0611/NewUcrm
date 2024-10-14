<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
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
    public function viewReviews()
    {
        $reviews = Review::query()
        ->select('*')
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
        $reviews = Review::query()
        ->select('*')
        ->where('item_id', $itemId)
        ->get();

        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreReviewRequest $request)
    {
        $reviews = new Review();

        $reviewCreateArray = [
            'customer_id' => $request->customer_id,
            'item_id' => $request->item_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ];
        $reviews->fill($reviewCreateArray);
        $reviews->save();

        return response()->json($reviews);
    }
}
