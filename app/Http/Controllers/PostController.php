<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   
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
        $post = new Post();
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStaffRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */


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
