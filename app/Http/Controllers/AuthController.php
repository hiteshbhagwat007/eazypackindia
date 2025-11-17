<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the register form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:4',
            'role' => 'required|in:super_admin,admin,manager,employee',
            'terms' => 'accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // Plain text password storage
            'password' => $request->password,
            'role' => $request->role,
            'status' => 'active',
        ]);

        Auth::login($user);

        // Redirect based on role
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isManager()) {
            return redirect()->route('manager.dashboard');
        }

        return redirect()->route('employee.dashboard');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->password === $request->password) {
            Auth::login($user, $request->has('remember'));
            
            // Redirect based on role
            if ($user->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            } elseif ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isManager()) {
                return redirect()->route('manager.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
