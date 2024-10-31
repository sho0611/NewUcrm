<?php

namespace App\Services;
use App\Models\Comment;
use App\Data\CommentData;
use App\Data\CommentResult;
use App\Interfaces\CommentSeverInterface;

class SaveComment implements CommentSeverInterface
{
    /**
     * コメントを保存する
     *
     * @param CommentData $commentData
     * @param integer|null $commentId
     * @return CommentResult
     */
    public function saveComment(CommentData $commentData, ?int $commentId = null):CommentResult
    {
        if ($commentId) {
            $comment = Comment::findOrFail($commentId);
            if (!$comment)
            {
                return response()->json(['error' => 'Appointment not found for ID: ' . $commentId]);
            }
            } else {
                $comment = new Comment(); 
            }

        $createCommentArray = [
            'content' => $commentData->content,
            'post_id' => $commentData->post_id,
            'customer_id' => $commentData->customer_id,
        ];
        $comment->fill($createCommentArray);
        $comment->save();

        return new CommentResult($comment);

    }

}