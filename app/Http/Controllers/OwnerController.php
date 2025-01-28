<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function dashboard()
    {
        return view('owner.dashboard');
    }

    public function showLoginForm()
    {
        return view('owner.login');
    }

    public function login(Request $request)
    {
        // Validate the phone number input
        $request->validate([
            'phone' => 'required|string',
        ]);

        // Find the user by phone number
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['phone' => 'User not found.']);
        }

        // Ensure the user is an owner
        if ($user->user_type !== 'owner') {
            return back()->withErrors(['phone' => 'You do not have permission to access this page.']);
        }

        // Log the user in using the 'owner' guard
        Auth::guard('owner')->login($user);

        // Redirect to the owner dashboard
        return redirect()->route('owner.dashboard')->with('success', 'Welcome to the Owner Dashboard!');
    }



    public function userManagement()
    {
        $admins = User::where('user_type', 'admin')->get();
        $users = User::where('user_type', 'user')->get();

        return view('owner.user-management', compact('admins', 'users'));
    }


    public function promote(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->id);

        if ($user->user_type == 'admin') {
            return redirect()->back()->with('error', 'User is already an Admin!');
        }

        $user->user_type = 'admin';
        $user->save();

        return redirect()->back()->with('success', 'User has been promoted to Admin successfully.');
    }


    public function unpromote(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->id);

        if ($user->user_type == 'user') {
            return redirect()->back()->with('error', 'User is already a regular user.');
        }

        $user->user_type = 'user';
        $user->save();

        return redirect()->back()->with('success', 'User has been unpromoted successfully.');
    }

    public function logout()
    {
        Auth::guard('owner')->logout();
        return redirect('/owner/login')->with('success', 'Logged out successfully.');
    }

    public function block(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:users,id', // Ensure the user exists
        ]);

        $user = User::find($request->id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        try {
            // Soft delete the user
            $user->delete();

            return redirect()->back()->with('success', 'User has been successfully blocked.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while blocking the user.');
        }
    }

    public function restore(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::withTrashed()->find($request->id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        try {
            // Restore the soft-deleted user
            $user->restore();

            return redirect()->back()->with('success', 'User has been successfully unblocked.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while unblocking the user.');
        }
    }
}
