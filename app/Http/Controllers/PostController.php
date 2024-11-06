<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Data\PostData;
use App\Interfaces\PostSaverInterface;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    private $postSaver;
    public function __construct(PostSaverInterface $postSaver)
    {
        $this->postSaver = $postSaver;
    }
    /**
     * 投稿内容の作成
     *
     * @param  \App\Http\Requests\StorePostRequest  $post
     * @return \Illuminate\Http\Response
     */
    public function post(StorePostRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts'); 
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
        $postData = new PostData(
            path: $path,
            staff_id: $request->staff_id,
            item_id: $request->item_id,
            description: $request->description
        );

        $postResult = $this->postSaver->savePost($postData);

        return response()->json($postResult->post);
    }
    
    /**
     * 投稿を更新
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function updatePost(int $postId,UpdatePostRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts'); 
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
        $postData = new PostData(
            path: $path,
            staff_id: $request->staff_id,
            item_id: $request->item_id,
            description: $request->description
        );
        $postResult = $this->postSaver->savePost($postData, $postId);

        return response()->json($postResult->post);
    }

    /**
     *投稿を削除
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function deletePost($postId)
    {   
        $post = Post::query()->findOrFail($postId);
        if ($postId) {
            $postId->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
}
