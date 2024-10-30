<?php

namespace App\Services;
use App\Models\Post;
use App\Data\PostData;
use App\Data\PostResult;

class SavePost 
{
    public function SavePost(PostData $postData):PostResult
    {
        $post = new Post();
        $createPostArray = [
            'psth' => $postData->path,
            'staff_id' => $postData->staff_id,
            'item_id' => $postData->item_id,
            'description' => $postData->description,
        ];

        $post->fill($createPostArray);
        $post->save();

        return new PostResult($post);
    }
}