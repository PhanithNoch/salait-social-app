<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('update',[AuthController::class,'update'])->middleware('auth:api');
Route::delete('delete-account/{id}',[AuthController::class,'destroy'])->middleware('auth:api');
Route::get('me',[AuthController::class,'me'])->middleware('auth:api');


// POSTS ROUTES
Route::get('posts',[PostController::class,'index'])->middleware('auth:api'); /// get all posts
Route::post('create-post',[PostController::class,'store'])->middleware('auth:api'); /// create a post
Route::post('update-post/{id}',[PostController::class,'update'])->middleware('auth:api'); /// update a post
Route::delete('delete-post/{id}',[PostController::class,'destroy'])->middleware('auth:api'); /// delete a post

// likes routes 
Route::post('like-dislike/{postId}',[LikeController::class,'likeDislike'])->middleware('auth:api'); /// like or dislike a post
Route::get('likes/{postId}',[LikeController::class,'show'])->middleware('auth:api'); /// get all likes of a post

// comment routes
Route::get('comment/{postId}',[CommentController::class,'show'])->middleware('auth:api'); /// get all comments of a post
Route::post('create-comment',[CommentController::class,'store'])->middleware('auth:api'); /// create a comment
Route::post('update-comment/{id}',[CommentController::class,'update'])->middleware('auth:api'); /// update a comment
Route::delete('delete-comment/{id}',[CommentController::class,'destroy'])->middleware('auth:api'); /// delete a comment
