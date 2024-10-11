<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;  

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        /* Inertia::render(): Sẽ mặc định truy cập đến folder resources/js/Pages */
        return Inertia::render('Admin/Auth/Login', [
            'canResetPassword' => Route::has('admin.password.request'),
            'status' => session('status'),
        ]);
    }

    public function login(Request $request) 
    {   
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password],
            $request->remember)) {
            return redirect()->route('admin.dashboard.index');
        }

        // return redirect()->route('admin.login')->with('error', 'Invalid credentials.');

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
            'password' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {        
        Auth::guard('web')->logout();
       
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
