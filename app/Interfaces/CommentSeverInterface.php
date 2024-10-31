<?php
namespace App\Interfaces;

use App\Data\CommentData;
use App\Data\CommentResult;

interface CommentSeverInterface
{
    public function saveComment(CommentData $commentData, ?int $commentId = null):CommentResult;
}