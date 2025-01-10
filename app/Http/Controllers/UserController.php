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
        'phone' => 'required|unique:users,phone',
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'user_type' => 'nullable|string',  
        'location' => 'nullable|string',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $profilePhoto = null;
    if ($request->hasFile('profile_photo')) {
        $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');  // Store the photo in the public disk
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

    $request->validate([
        'phone' => 'nullable|unique:users,phone,' . $user->id,
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

    $user->update($request->only(['phone', 'first_name', 'last_name', 'user_type', 'location']) + ['profile_photo' => $profilePhoto]);

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




public function wlogin(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
    ]);

    $user = User::where('phone', $request->phone)->first();


    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

   
    Auth::login($user);

   
    return redirect('/');
}




public function promote(Request $request)
{
    $request->validate([
        'phone' => 'required|numeric',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return redirect('/')->with('error', 'User not found.');
    }

    $user->user_type = 'admin';
    $user->save();

    return redirect('/')->with('message', 'User has been promoted to Admin Successfully.');
}


public function unpromote(Request $request, $id)
{
   

    $user = User::find($id);

    if (!$user) {
        return redirect('/')->with('error', 'User not found.');
    }

    $user->user_type = 'user';
    $user->save();

    return redirect('/')->with('message', 'User has been unpromoted successfully.');
}


public function adminIndex(Request $request)
{
    $adminUsers = User::where('user_type', 'admin')->get();
    return view('welcome', compact('adminUsers'));
}



}
