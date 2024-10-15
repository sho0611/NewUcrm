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
    public function reviewForm(Request $request)
    {
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
        $item = Item::where('name', $request->service_id)->first();

        if (empty($item)) {
            return response()->json(['error' => '指定されたサービスが見つかりません。'], 404);
        }

        $reviews = new Review();

        $reviewCreateArray = [
            'service_id'=> $item->id,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment
        ];

        $reviews->fill($reviewCreateArray);
        $reviews->save();

        return response()->json($reviews ,200);
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
    public function viewItemReviews(int $serviceId, Request $request)
    {
        $reviews = Review::with(['item'])
        ->where('service_id', $serviceId)
        ->get();

        return response()->json($reviews);
    }
}
