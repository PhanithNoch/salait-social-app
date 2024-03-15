<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Validator;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function show($postId){
        $post = Post::find($postId);
        if(!$post){
            return response()->json([
                'message'=>'Post not found',
            ],404);
        }
        $comments = $post->comments()->with('user')->latest()->get();
        return response()->json([
            'comments'=>$comments
        ]);
    
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
            'post_id'=>'required|integer'
        ]);
        if($validator->fails()){
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $data = $request->all();
        $user = auth()->user();
        $post = Post::find($data['post_id']);
        if(!$post){
            return response()->json([
                'message'=>'Post not found',
            ],404);
        }
        $comment = Comment::create([
            'user_id'=>$user->id,
            'text'=>$data['text'],
            'post_id'=>$post->id
        ]);
        return response()->json([
            'comment'=>$comment,
            'message'=>'Comment created successfully'
        ]);
    }

    public function update(Request $request,$id){
        $data = $request->all();
        $user = Auth::user(); /// current user logged in
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json([
                'message'=>'Comment not found',
            ],404);
        }
        if($user->id == $comment->user_id){
            $comment->update($data);
            return response()->json([
                'comment'=>$comment,
                'message'=>'Comment updated successfully'
            ]);
        }
        else{
            return response()->json([
                'message'=>'You are not authorized to update this comment'
            ]);
        }


    }
    
    public function destroy($id){
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json([
                'message'=>'Comment not found',
            ],404);
        }
        $user = Auth::user(); /// current user logged in
        if($user->id == $comment->user_id){
            $comment->delete();
            return response()->json([
                'message'=>'Comment deleted successfully'
            ]);
        }
        else{
            return response()->json([
                'message'=>'You are not authorized to delete this comment'
            ]);
        }
    }
}
