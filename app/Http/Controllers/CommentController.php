<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postComment(Request $request)
    {
        $comment = new Comment();
        $commentCreateArray = [
            'content' => $request->content,
            'post_id' => $request->post_id,
            'customer_id' => $request->customer_id,
        ];
        $comment ->fill($commentCreateArray);
        $comment ->save();

        return response()->json($comment);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function updateComment(int $itemsId, Request $request)
    {
        $comment = Comment::query()->findOrFail($itemsId);
        $commentCreateArray = [
            'content' => $request->content,
            'post_id' => $request->post_id,
            'customer_id' => $request->customer_id,
        ];
        $comment ->fill($commentCreateArray);
        $comment ->save();

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
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
