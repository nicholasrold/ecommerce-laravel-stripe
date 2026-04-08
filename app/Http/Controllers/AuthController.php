<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * VIEW: Admin Login Page
     */
    public function showAdminLogin()
    {
        return view('auth.login_admin');
    }

    /**
     * LOGIC: Admin Authentication
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Using the 'admin' guard for system access
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return back()->with('error', 'Identity mismatch for system access.');
    }

    /**
     * LOGIC: Admin Logout
     */
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }

    /**
     * LOGIC: User Registration
     */
    public function register(Request $request) 
    {
        // Input validation
        $request->validate([
            'username' => 'required|unique:users,username|max:10',
            'password' => 'required|min:6',
            'full_name' => 'required',
        ]);

        $user = User::create([
            'full_name'      => $request->full_name,
            'username'       => $request->username,
            'phone_number'   => $request->phone_number,
            'province'       => $request->province,
            'city'           => $request->city,
            'postal_code'    => $request->postal_code,
            'address_detail' => $request->address_detail,
            'password'       => Hash::make($request->password), 
        ]);
        
        Auth::login($user);
        return redirect('/');
    }

    /**
     * LOGIC: User Login (with debugging logic)
     */
    public function login(Request $request) 
    {
        // Match the 'user_email' name attribute from the Blade view
        $username = $request->user_email; 
        $password = $request->password;

        // Attempt login using default web guard
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        // FAILURE HANDLING: Debugging reasons (Local development only)
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            return back()->with('error', 'DEBUG: Username not found in database.');
        }

        if (!Hash::check($password, $user->password)) {
            return back()->with('error', 'DEBUG: Password incorrect (Hash mismatch).');
        }
        
        return back()->with('error', 'Invalid Credentials');
    }

    /**
     * LOGIC: User Logout
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}