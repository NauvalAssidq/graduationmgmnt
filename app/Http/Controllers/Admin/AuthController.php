<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // tampilan login
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    // fungsi dan validasi login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'], // Required email berarti email harus mengandung karakter @
            'password' => ['required'], // Required password berarti password harus diisi
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // fungsi logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
