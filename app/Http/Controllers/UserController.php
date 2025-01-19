<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\log;                                                     

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show(Request $request)
    {
        $user = $request->user();
    
        if ($user) {
            return response()->json($user);
        } else {
            $statusCode = 404;
            return response()->json(['message' => 'User not found',
            '$status_code'=>$statusCode], $statusCode);
        }
    }
    
    
    public function store(Request $request)
{
    $request->validate([
       'phone' => [
        'required',
        'string',
        'regex:/^\+?[0-9]{8,11}$/'
    ],
    ]);

    if (User::where('phone', $request->phone)->exists()) {
        $statusCode = 422;
        return response()->json(['message' => 'The phone number is already taken.'
        , 'status_code'=>$statusCode], $statusCode);
    }

    $request->validate([
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'user_type' => 'nullable|string',
        'location' => 'nullable|string',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $profilePhoto = null;
    if ($request->hasFile('profile_photo')) {
        $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
    }

    $userType = $request->user_type ?? 'user';

    $user = User::create([
        'phone' => $request->phone,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'user_type' => $userType,
        'location' => $request->location,
        'profile_photo' => $profilePhoto,
    ]);

    return response()->json($user, 201);
}


public function update(Request $request)
{
    $user = $request->user();

    if ($request->has('phone') && $request->phone !== $user->phone) {
        if (User::where('phone', $request->phone)->exists()) {
            $statusCode = 422;
            return response()->json(['message' => 'The phone number is already taken.', 
                                '$status_code'=>$statusCode], $statusCode);
        }
    }

    $request->validate([
        $request->validate([
            'phone' => [
                'nullable',
                'unique:users,phone,' . $user->id,
                'regex:/^\+?[0-9]{8,11}$/'  
            ],
        ]),
       'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'user_type' => 'nullable|string',
        'location' => 'nullable|string',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $profilePhoto = $user->profile_photo;

    if ($request->hasFile('profile_photo')) {
        if ($profilePhoto) {
            Storage::disk('public')->delete($profilePhoto);
        }

        $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
    }

    $user->update(
        $request->only(['phone', 'first_name', 'last_name', 'user_type', 'location']) +
        ['profile_photo' => $profilePhoto]
    );

    return response()->json($user);
}


    public function destroy(Request $request)
{
    $user = $request->user();

    if (!$user) {
        $statusCode = 404;
        return response()->json(['message' => 'User not found',
        '$status_code'=>$statusCode], $statusCode);
    }

    if ($user->profile_photo) {
        Storage::disk('public')->delete($user->profile_photo);
    }

    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
}



    public function login(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
    ]);
    
    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        $statusCode = 404;
        return response()->json(['message' => 'User not found',
                                'status_code'=>$statusCode], $statusCode);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        'user' => $user,
    ]);
}


public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logout successful']);
}




}
