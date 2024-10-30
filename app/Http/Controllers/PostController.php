<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\SavePost;
use Illuminate\Http\Request;
use App\Data\PostData;

class PostController extends Controller
{
    private $savePost;
    public function __construct(SavePost $savePost)
    {
        $this->savePost = $savePost;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $post
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts'); 
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
        $postData = new PostData(
            path: $request->$path,
            staff_id: $request->staff_id,
            item_id: $request->item_id,
            description: $request->description
        );

        $postResult = $this->savePost->savePost($postData);

        return response()->json($postResult->$post);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function updatePost(int $postId,Request $request)
    {
        $post = Post::query()->findOrFail($postId);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts'); 
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }

        $postCreatArray = [
            'image' => $path,
            'staff_id' => $request->staff_id,
            'item_id' => $request->item_id,
            'description' => $request->description,
        ];

        $post->fill($postCreatArray)->save();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
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
