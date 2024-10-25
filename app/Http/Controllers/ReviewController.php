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
        $reviews = Review::query()
            ->join('items', 'reviews.item_id', '=', 'items.item_id')
            ->select(
                'items.item_id AS item_id',
                'items.name AS item_name',
                'items.price AS item_price',
                'reviews.customer_name',
                'reviews.rating',
                'reviews.comment',
                'reviews.created_at'
            )
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
        ->where('item_id', $itemId)
        ->get();
        return response()->json($reviews);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReviewRequest  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function updateReviews(int $reviewId, UpdateReviewRequest $request)
    {
        $reviews = Review::query()->findOrFail($reviewId);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function deleteReviews($reviewId)
    {
        $review = Review::query()->findOrFail($reviewId);
        if ($review) {
            $review->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
}

   
