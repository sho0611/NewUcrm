<?php

namespace App\Services;
use App\Models\Post;
use App\Data\PostData;
use App\Data\PostResult;
use App\Interfaces\PostSaverInterface;

class SavePost implements PostSaverInterface
{
  /**
   * 投稿内容を保存する
   * idがあれば更新、なければ新規作成
   *
   * @param PostData $postData
   * @param integer|null $postId
   * @return PostResult
   */
    public function savePost(PostData $postData, ?int $postId = null):PostResult
    {
        if ($postId) {
            $post = Post::findOrFail($postId);
            if (!$post)
            {
                return response()->json(['error' => 'Appointment not found for ID: ' . $postId]);
            }
        } else {
            $post = new Post();
        }
      
        $createPostArray = [
            'image' => $postData->path,
            'staff_id' => $postData->staff_id,
            'item_id' => $postData->item_id,
            'description' => $postData->description,
        ];

        $post->fill($createPostArray);
        $post->save();

        return new PostResult($post);
    }
}