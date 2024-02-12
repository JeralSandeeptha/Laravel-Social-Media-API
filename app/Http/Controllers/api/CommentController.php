<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function createComment(Request $request) {

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
            'userId' => 'required|integer',
            'postId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $comment = new Comment();
            $comment->comment = $request->input('comment');
            $comment->userId = $request->input('userId');
            $comment->postId = $request->input('postId');

            $comment->save();

            return response()->json([
                'statusCode' => 201,
                'message' => 'Create comment query was successful',
                'data' => $comment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Create comment query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAllComments() {
        try {
            $comments = Comment::all();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Get all comments query was successfully',
                'data' => $comments,
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get all comments query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getComment(Request $request) {
        try {
            $commentId = $request->route('commentId');
    
            if($commentId) {
                $comment = Comment::find($commentId);
    
                if($comment) {
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Get comments query was successful',
                        'data' => $comment,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Comment not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid comment ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get comment query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateComment(Request $request) {
        try {
            $commentId = $request->route('commentId');
    
            if($commentId) {
                $comment = Comment::find($commentId);
    
                if($comment) {
                    $comment->comment = $request->comment;
                    $updatedComment = $comment->update();
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Update comment query was successful',
                        'data' => $updatedComment,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Comment not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid Comment ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Update comment query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteComment(Request $request) {
        try {
            $commenId = $request->route('commentId');
    
            if($commenId) {
                $comment = Comment::find($commenId);
    
                if($comment) {
                    $comment->delete();
                    return response()->json([
                        'statusCode' => 204,
                        'message' => 'Delete comment query was successful',
                    ], 204);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Comment not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid comment ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Delete comment query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
