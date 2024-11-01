<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Interfaces\ReviewSaverInterface;
use App\Data\ReviewData;

class ReviewController extends Controller
{
    protected ReviewSaverInterface $reviewSaver;

    public function __construct(ReviewSaverInterface $reviewSaver)
    {
        $this->reviewSaver = $reviewSaver;
    }

    /**
     * 新しいレビューを作成する
     *
     * @param StoreReviewRequest $request
     * @return JsonResponse
     */
    public function createReview(StoreReviewRequest $request)
    {
        $reviewData = new ReviewData(
            item_id: $request->item_id,
            customer_name: $request->customer_name,
            rating: $request->rating,
            comment: $request->comment
        );

        $reviewResult = $this->reviewSaver->saveReview($reviewData);

        return response()->json($reviewResult->review);
    }

    /**
     * レビューを変更、更新する
     *
     * @param  \App\Http\Requests\UpdateReviewRequest  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function updateReviews(int $reviewId, UpdateReviewRequest $request)
    {
        $reviewData = new ReviewData(
            item_id: $request->item_id,
            customer_name: $request->customer_name,
            rating: $request->rating,
            comment: $request->comment
        );

        $reviewResult = $this->reviewSaver->saveReview($reviewData, $reviewId);

        return response()->json($reviewResult->review);
    }

    /**
     * レビューを削除する
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

    /**
     * アイテムごとのレビューを表示する
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
     * 全てのレビューを表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function viewReviews()
    {
        $reviews = Review::getReviewsWithItems();
        return response()->json($reviews);
    }
}

