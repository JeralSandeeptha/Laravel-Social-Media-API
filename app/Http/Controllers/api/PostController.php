<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function createPost(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'imageUrl' => 'required|string',
            'userId' => 'required|integer',
            'likes' => 'nullable|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {

            $user = User::find($request->input('userId'));

            if($user) {
                $post = new Post();
                $post->title = $request->input('title');
                $post->imageUrl = $request->input('imageUrl');
                $post->userId = $request->input('userId');
                $post->likes = $request->input('likes') ?? [];
                $post->save();
                
                return response()->json([
                    'statusCode' => 201,
                    'message' => 'Create post query was successfully',
                    'data' => $post,
                ], 201);
            }else {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'User id not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Create post query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }  

    public function getAllPosts() {
        try {
            $posts = Post::all();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Get all posts query was successfully',
                'data' => $posts,
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get all posts query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPost(Request $request) {
        try {
            $postsId = $request->route('postId');
    
            if($postsId) {
                $post = Post::find($postsId);
    
                if($post) {
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Get posts query was successful',
                        'data' => $post,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Post not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid post ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get post query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePost(Request $request) {
        try {
            $postId = $request->route('postId');
    
            if($postId) {
                $post = Post::find($postId);
    
                if($post) {
                    $post->title = $request->title;
                    $post->imageUrl = $request->imageUrl;
                    $updatedPost = $post->update();
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Update post query was successful',
                        'data' => $updatedPost,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Post not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid post ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Update post query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deletePost(Request $request) {
        try {
            $postId = $request->route('postId');
    
            if($postId) {
                $post = Post::find($postId);
    
                if($post) {
                    $post->delete();
                    $comments = Comment::where('postId', $postId)->delete();
                    return response()->json([
                        'statusCode' => 204,
                        'message' => 'Delete post query was successful',
                    ], 204);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Post not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid post ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Delete post query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function likePost(Request $request) {
        try {
            $postId = $request->route('postId');
            $userId = $request->route('userId');

            $post = Post::find($postId);
            
            // Check if $post is null
            if (!$post) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found',
                ], 404);
            }
    
            if (!$userId) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid user ID',
                ], 400);
            }
    
            $likes_array = $post->likes ?? [];
            $likes_array[] = $userId;
            $post->likes = $likes_array;
            $post->save();
    
            return response()->json([
                'statusCode' => 201,
                'message' => 'Post liked successfully',
                'data' => $post,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Failed to like post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function dislikePost($userId, $postId) {
        try {
            $post = Post::find($postId);
            
            if (!$post) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found',
                ], 404);
            }
    
            if (!$userId) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid user ID',
                ], 400);
            }
    
            $likes_array = $post->likes ?? [];
    
            // Check if user has already liked the post
            if (in_array($userId, $likes_array)) {
                // User has liked the post, proceed with unlike operation
                $key = array_search($userId, $likes_array);
                unset($likes_array[$key]);
                $post->likes = $likes_array;
                $post->save();
    
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Post unliked successfully',
                    'data' => $post,
                ], 200);
            } else {
                // User has not liked the post, return response
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'User has not liked this post',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Failed to unlike post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
