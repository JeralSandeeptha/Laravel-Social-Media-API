<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function registerAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            $admin = new Admin();
            $admin->username = $request->input('username');
            $admin->password = $request->input('password');
            $admin->save();
            
            return response()->json([
                'statusCode' => 201,
                'message' => 'Admin register query was successfully',
                'data' => $admin,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Admin register query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAllAdmins() {
        try {
            $admins = Admin::all();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Get all admins query was successfully',
                'data' => $admins,
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get all admins query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAdmin(Request $request) {
        try {
            $adminId = $request->route('adminId');
    
            if($adminId) {
                $admin = Admin::find($adminId);
    
                if($admin) {
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Get admin query was successful',
                        'data' => $admin,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Admin not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid admin ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Get admin query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAdmin(Request $request) {
        try {
            $adminId = $request->route('adminId');
    
            if($adminId) {
                $admin = Admin::find($adminId);
    
                if($admin) {
                    $admin->username = $request->username;
                    $admin->password = $request->password;
                    $updatedAdmin = $admin->update();
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Update admin query was successful',
                        'data' => $updatedAdmin,
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Admin not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid admin ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Update admin query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAdmin(Request $request) {
        try {
            $adminId = $request->route('adminId');
    
            if($adminId) {
                $admin = Admin::find($adminId);
    
                if($admin) {
                    $admin->delete();
                    return response()->json([
                        'statusCode' => 204,
                        'message' => 'Delete query was successful',
                    ], 204);
                } else {
                    return response()->json([
                        'statusCode' => 404,
                        'message' => 'Admin not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Invalid admin ID',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Delete admin query was failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
