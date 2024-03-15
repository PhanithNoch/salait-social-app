<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Like;
class LikeController extends Controller
{
    public function likeDislike($postId){
        $user = Auth::user(); /// current user logged in
        $post = Post::find($postId);
        if($post != null){
            $liked =  Like::where('user_id',$user->id)->where('post_id',$post->id)->first();
            if($liked){
                $liked->delete();
                return response()->json([
                    'message'=>'Post unliked successfully'
                ],200);
            }
            else{
                $like = new Like();
                $like->user_id = $user->id;
                $like->post_id = $post->id;
                $like->save();
                return response()->json([
                    'message'=>'Post liked successfully'
                ],200);

                
            }
        }
        else{
            return response()->json([
                'message'=>'Post not found',
            ],404);
        }
    }
    public function show($postId){
        $post = Post::find($postId);
        if($post != null){
            $likes = $post->likes()->with('user')->latest()->get();
            return response()->json([
                'likes'=>$likes
            ]);
        }
        else{
            return response()->json([
                'message'=>'Post not found',
            ],404);
        }
    }
}
