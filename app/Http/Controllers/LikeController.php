<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLike(Request $request)
    {
        $like = new Like();
        $likeArray = [
            'post_id' => $request->post_id,
            'customer_id' => $request->customer_id
        ];
        $like->fill($likeArray)->save();

        return response()->json($like);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function deleteLike($likeId)
    {
        // $like = Like::find($likeId);
        $like = Like::query()->findOrFail($likeId);
        if ($like) {
            $like->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
    
    
}
