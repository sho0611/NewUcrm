<?php

namespace App\Interfaces;

use App\Data\ReviewData;
use App\Data\ReviewResult;

interface ReviewSaverInterface
{
    public function saveReview(ReviewData $reviewData, ?int $reviewId = null): ReviewResult;
}
