<?php

namespace App\Services;

use App\Models\Review;
use App\Data\ReviewData;
use App\Data\ReviewResult;
use App\Interfaces\ReviewSaverInterface;

class SaveReview implements ReviewSaverInterface
{
    /**
     * レビュー内容を保存する
     * idがあれば更新、なければ新規作成
     *
     * @param ReviewData $reviewData
     * @param integer|null $reviewId
     * @return ReviewResult
     */
    public function saveReview(ReviewData $reviewData, ?int $reviewId = null): ReviewResult
    {
        if ($reviewId) {
            $review = Review::findOrFail($reviewId);
            if (!$review) {
                return response()->json(['error' => 'Review not found for ID: ' . $reviewId]);
            }
        } else {
            $review = new Review();
        }

        $reviewCreateArray = [
            'item_id' => $reviewData->item_id,
            'customer_name' => $reviewData->customer_name,
            'rating' => $reviewData->rating,
            'comment' => $reviewData->comment
        ];

        $review->fill($reviewCreateArray);
        $review->save();

        return new ReviewResult($review);
    }
}
