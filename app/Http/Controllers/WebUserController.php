<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class WebUserController extends Controller
{
    public function create()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);
    
        // Find the user by phone number
        $user = User::where('phone', $request->phone)->first();
    
        if (!$user) {
            return back()->withErrors(['phone' => 'User not found.']);
        }
    
        // Check if the user has admin access
        if ($user->user_type !== 'admin') {
            return back()->withErrors(['phone' => 'You do not have permission to access this page.']);
        }
    
        // Log the user in using Laravel's Auth system
        Auth::login($user);
    
        // Redirect to the dashboard
        return redirect()->route('dashboard')->with('success', 'Welcome to the dashboard!');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

}

