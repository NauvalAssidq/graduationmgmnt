<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // tampilan login
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    // fungsi dan validasi login (email atau NIP)
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = $request->email;
        $password   = $request->password;

        // Deteksi: jika mengandung '@' → login via email, jika tidak → login via NIP
        if (str_contains($identifier, '@')) {
            // Login via email
            $loggedIn = Auth::attempt([
                'email'    => $identifier,
                'password' => $password,
            ], $request->boolean('remember'));
        } else {
            // Login via NIP — cari admin, lalu verifikasi password manual
            $admin = Admin::where('nip', $identifier)->first();

            if ($admin && Hash::check($password, $admin->password)) {
                Auth::login($admin, $request->boolean('remember'));
                $loggedIn = true;
            } else {
                $loggedIn = false;
            }
        }

        if ($loggedIn) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email/NIP atau password salah.',
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
