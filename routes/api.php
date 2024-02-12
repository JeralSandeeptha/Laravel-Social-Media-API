<?php

use App\Http\Controllers\api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Admin endpoints
Route::post('admin/auth/register', [AdminController::class, 'registerAdmin']);
Route::get('admin', [AdminController::class, 'getAllAdmins']);
Route::get('admin/{adminId}', [AdminController::class, 'getAdmin']);
Route::put('admin/{adminId}', [AdminController::class, 'updateAdmin']);
Route::delete('admin/{adminId}', [AdminController::class, 'deleteAdmin']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
