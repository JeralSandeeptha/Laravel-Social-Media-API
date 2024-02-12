<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Admin endpoints
Route::post('admin/auth/register', [AdminController::class, 'registerAdmin']);
Route::get('admin', [AdminController::class, 'getAllAdmins']);
Route::get('admin/{adminId}', [AdminController::class, 'getAdmin']);
Route::put('admin/{adminId}', [AdminController::class, 'updateAdmin']);
Route::delete('admin/{adminId}', [AdminController::class, 'deleteAdmin']);

// User endpoints
Route::post('user/auth/register', [UserController::class, 'registerUser']);
Route::get('user/list', [UserController::class, 'getAllUsers']); //cant directly use only user because of authentication by default
Route::get('user/{userId}', [UserController::class, 'getUser']);
Route::put('user/{userId}', [UserController::class, 'updateUser']);
Route::delete('user/{userId}', [UserController::class, 'deleteUser']);
Route::get('user/getByUserUsername/{username}', [UserController::class, 'getUserByUsername']);

// Post endpoints
Route::post('post', [PostController::class, 'createPost']);
Route::get('post', [PostController::class, 'getAllPosts']);
Route::get('post/{postId}', [PostController::class, 'getPost']);
Route::put('post/{postId}', [PostController::class, 'updatePost']);
Route::delete('post/{postId}', [PostController::class, 'deletePost']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
