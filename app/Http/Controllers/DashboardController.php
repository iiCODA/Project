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
            $statusCode = 404;
            return response()->json(['error' => 'User not found.', 'status_code'=>$statusCode], $statusCode);
        }
    
        if ($user->user_type !== 'owner') {
            $statusCode = 403;
            return response()->json(['message' => 'You do not have permission to access this page.',
            '$status_code'=>$statusCode], $statusCode);
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
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
    
    
    public function promote(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);
    
        $user = User::where('phone', $request->phone)->first();
    
        if (!$user) {
            $statusCode = 404;
            return response()->json(['message' => 'User not found',
                                    'status_code'=>$statusCode], $statusCode);
        }
    
        if($user->user_type == 'admin'){
            return response()->json (['message'=> 'User is Already an Admin!.']);

        }

        $user->user_type = 'admin';
        $user->save();
    
        return response()->json (['message'=> 'User has been promoted to Admin Successfully.']);
    }
    
    
    public function unpromote(Request $request, $id)
    {
    
        $user = User::find($id);
    
        if (!$user) {
            $statusCode = 404;
            return response()->json(['message' => 'User not found',
                                    'status_code'=>$statusCode], $statusCode);
        }
    

        if($user->user_type == 'user'){
            return response()->json (['message'=> 'The Type of the User is Already a user! he is not an Admin.']);
        }

        $user->user_type = 'user';
        $user->save();

        return response()->json (['message'=> 'User has been unpromoted to user type Successfully.']);
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
