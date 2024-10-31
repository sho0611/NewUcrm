<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Data\CommentData;
use App\Interfaces\CommentSeverInterface;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    private $commentSaver;
    public function __construct(CommentSeverInterface $commentSaver)
    {
        $this->commentSaver = $commentSaver
;   }
    
    /**
     * コメントの作成
     *
     * @return \Illuminate\Http\Response
     */
    public function postComment(StoreCommentRequest $request)
    {
        $commentData = new CommentData(
        content: $request->content,
        post_id: $request->post_id,
        customer_id: $request->customer_id,
        );

        $commentResult = $this->commentSaver->saveComment($commentData);

        return response()->json($commentResult->comment);
    }

    /**
     * コメントの変更、更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function updateComment(int $commentId, UpdateCommentRequest $request)
    {
        $commentData = new CommentData(
            content: $request->content,
            post_id: $request->post_id,
            customer_id: $request->customer_id,
            );
    
            $commentResult = $this->commentSaver->saveComment($commentData, $commentId);
    
            return response()->json($commentResult->comment);
    }

    /**
     * コメントの削除
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function deleteComment($commentId)
    {
        $comment = Comment::query()->findOrFail($commentId);
        if ($comment) {
            $comment->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
}
