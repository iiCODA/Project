<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DashboardController extends Controller
{
    
    public function wlogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);
    
        $user = User::where('phone', $request->phone)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
    
        if ($user->user_type !== 'owner') {
            return response()->json([
                'message' => 'You do not have permission to access this page.',
            ], 403); 
        }

        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user
        ], 200);
    }
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
    
    
    public function promote(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
        ]);
    
        $user = User::where('phone', $request->phone)->first();
    
        if (!$user) {
            $statusCode = 404;
            return response()->json(['message' => 'User not found',
                                    'status_code'=>$statusCode], $statusCode);
        }
    
        $user->user_type = 'admin';
        $user->save();
    
        return response()->json (['message', 'User has been promoted to Admin Successfully.']);
    }
    
    
    public function unpromote(Request $request, $id)
    {
    
        $user = User::find($id);
    
        if (!$user) {
            $statusCode = 404;
            return response()->json(['message' => 'User not found',
                                    'status_code'=>$statusCode], $statusCode);
        }
    
        $user->user_type = 'user';
        $user->save();

        return response()->json (['message', 'User has been promoted to Admin Successfully.']);
    }
    
    
    public function adminIndex(Request $request)
    {
        $adminUsers = User::where('user_type', 'admin')->get();
        return response()->json (['Admins' =>$adminUsers]);
    }
    
    public function userIndex(Request $request)
    {
        $Users = User::where('user_type', 'user')->get();
        return response()->json (['Users' =>$Users]);
    }
    

    }
