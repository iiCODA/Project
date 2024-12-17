<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // show all users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Show a single user (all his detalis)
    public function show(Request $request)
    {
        // Get the logged-in user
        $user = $request->user();
    
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'location' => 'nullable|string',  
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $profilePhoto = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');  // Store the photo in the public disk
        }

        // Create  a user
        $user = User::create([
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'location' => $request->location, 
            'profile_photo' => $profilePhoto,  
        ]);

        return response()->json($user, 201); 
    }

    // Update a user info (you can update what ever you want)
    public function update(Request $request)
{
    $user = $request->user(); // Get the currently authenticated user

    // Validate the incoming data
    $request->validate([
        'phone' => 'nullable|unique:users,phone,' . $user->id, // Ensure phone number is unique except for the current user
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'location' => 'nullable|string',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional profile photo
    ]);

    // Initialize the profile photo to the existing one if not uploaded
    $profilePhoto = $user->profile_photo;

    // Check if a new profile photo is being uploaded
    if ($request->hasFile('profile_photo')) {
        // Delete the old photo from storage if it exists
        if ($profilePhoto) {
            Storage::disk('public')->delete($profilePhoto);
        }

        // Store the new photo and update the profile photo field
        $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
    }

    // Update the user's information with the validated data
    $user->update($request->only(['phone', 'first_name', 'last_name', 'location']) + ['profile_photo' => $profilePhoto]);

    // Return the updated user as a response
    return response()->json($user);
}



    // Delete a user
    public function destroy(Request $request)
{
    // Get the logged-in user
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Delete the user's profile photo if it exists
    if ($user->profile_photo) {
        Storage::disk('public')->delete($user->profile_photo);
    }

    // Delete the user
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
        return response()->json(['message' => 'User not found'], 404);
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