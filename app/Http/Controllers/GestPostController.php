<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Data\PostData;
use App\Interfaces\PostSaverInterface;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;


class GestPostController extends Controller
{
    /**
     * 投稿を取得
     *
     * @return \Illuminate\Http\Response
     */ 
    public function viewPosts(Request $request)
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /**
     * 投稿に対するいいね数を取得
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */ 
    public function countLikesbyPost($postId)
    {
        $likeCount = Like::where('post_id', $postId)->count();
        return response()->json(['post_id' => $postId, 'like_count' =>
        $likeCount]);
    }

     /**
     * 投稿に対するコメントを取得
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */ 
    public function getPostComments($postId)
    {
        $comments = Comment::where('post_id', $postId)
        ->OrderBy('created_at', 'desc')->get();
        return response()->json($comments); 
    }

    /**
     * アイテムに紐づく投稿を取得
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */ 
    public function getItemPost($itemId)
    {
        $items = Item::where('item_id', $itemId)->get();
        $itemId = $items->pluck('item_id');

        $posts = Post::where('item_id', $itemId)->get();
        $itemArray = $items->toArray();
        $itemArray['posts'] = $posts->toArray();

        return response()->json($itemArray);
    }
}
