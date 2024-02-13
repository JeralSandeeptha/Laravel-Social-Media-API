<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function registerUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'city' => 'required|string',
            'contactNumber' => 'required|string',
            'postalCode' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            $user = new User();
            $user->username = $request->input('username');
            $user->password = $request->input('password');
            $user->city = $request->input('city');
            $user->contactNumber = $request->input('contactNumber');
            $user->postalCode = $request->input('postalCode');
            $user->save();
            
            return response()->json([
                'statusCode' => 201,
                'message' => 'User register query was successfully',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'User register query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }  

    public function getAllUsers() {
        try {
            $users = User::all();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Get all users query was successfully',
                'data' => $users,
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get all users query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUser(Request $request) {
        try {
            $userId = $request->route('userId');
    
            if($userId) {
                $user = User::find($userId);
    
                if($user) {
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Get users query was successful',
                        'data' => $user,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'User not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid user ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get user query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateUser(Request $request) {
        try {
            $userId = $request->route('userId');
    
            if($userId) {
                $user = User::find($userId);
    
                if($user) {
                    $user->username = $request->username;
                    $user->password = $request->password;
                    $user->city = $request->city;
                    $user->contactNumber = $request->contactNumber;
                    $user->postalCode = $request->postalCode;
                    $updatedUser = $user->update();
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Update user query was successful',
                        'data' => $updatedUser,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'User not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid user ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Update user query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteUser(Request $request) {
        try {
            $userId = $request->route('userId');
    
            if($userId) {
                $user = User::find($userId);
    
                if($user) {
                    $user->delete();
                    Post::where('userId', $userId)->delete();
                    Comment::where('userId', $userId)->delete();
                    return response()->json([
                        'statusCode' => 204,
                        'message' => 'Delete user query was successful',
                    ], 204);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'User not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid user ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Delete user query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUserByUsername($username){
        try {
            $user = User::where('username', $username)->first();
            if (!$user) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid username',
                ], 400);
            }else {
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Get user by username query was successful',
                    'data' => $user,
                ], 200);
            }
        }catch(\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get user by username query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
