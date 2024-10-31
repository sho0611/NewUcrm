<?php
namespace App\Interfaces;

use App\Data\PostData;
use App\Data\PostResult;

interface PostSaverInterface
{
    public function savePost(PostData $postData, ?int $postId = null):PostResult;
}