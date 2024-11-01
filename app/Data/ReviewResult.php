<?php

namespace App\Data;

use App\Models\Review;

class ReviewResult
{
    public Review $review;

    /**
     * コンストラクタ
     *
     * @param Review $review レビュー情報を受け取ります
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }
}


