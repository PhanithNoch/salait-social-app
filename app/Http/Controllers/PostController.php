<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    // GET ALL POSTS
    public function index(){
        $user = Auth::user(); /// current user logged in
        $posts = Post::with('user')->latest()->paginate(20);
        foreach($posts as $post){
            $post->liked = $post->likes->contains('user_id', $user->id);
            $post->likeCount = $post->likes->count();
            $post->commentCount = $post->comments->count();
        }
        return response()->json([
            'posts'=>$posts
        ]);
    }

    public function store(Request $request){
        $data = $request->all(); 
        $user = Auth::user(); /// current user logged in
        if($user != null){
            if($request->hasFile('image')){
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $distinationPath = public_path('/posts');
                $image->move($distinationPath, $name);
                $data['image'] = $name;
            }
            $data['user_id'] = $user->id;
            $post = Post::create($data);
            return response()->json([
                'post'=>$post,
                'message'=>'Post created successfully'
            ]);
        }
        else{
            return response()->json([
                'message'=>'User not found'
            ]);
        }
    }

    public function update(Request $request,$id){
        $data = $request->all();
        $post = Post::find($id);
        if($post != null){
            $user = Auth::user(); /// current user logged in
            if($user->id == $post->user_id){
                if($request->hasFile('image')){
                    $image = $request->file('image');
                    $name = time() . '.' . $image->getClientOriginalExtension();
                    $distinationPath = public_path('/posts');
                    $image->move($distinationPath, $name);
                    $data['image'] = $name;
                    $oldImage = public_path('/posts/').$post->image;
                    if(file_exists($oldImage)){
                        unlink($oldImage);
                    }
                }
                $post->update($data);
                return response()->json([
                    'message'=>'Post updated successfully',
                    'post'=>$post
                ]);
            }
            else{
                return response()->json([
                    'message'=>'You are not authorized to update this post'
                ]);
            }

        }
        else{
            return response()->json([
                'message'=>'Post not found'
            ]);
        }
    }

    public function destroy($id){
        $user = Auth::user();
        $post = Post::find($id);
        if($post != null){
            if($post->user_id == $user->id){
                $post->delete();
                return response()->json([
                    'message'=>'Post deleted successfully'
                ]);
            }
        }
        else{
            return response()->json([
                'message'=>'Post not found'
            ]);
        }
    }
}
