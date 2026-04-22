<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'account');
        $apiSources = ApiSource::with('bukuWisuda')->latest()->get();
        return view('admin.settings.index', compact('tab', 'apiSources'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user(); // User maps to Admin

        $request->validate([
            'email'            => 'required|email|unique:admin,email,' . $user->admin_id . ',admin_id',
            'current_password' => 'nullable|required_with:new_password',
            'new_password'     => 'nullable|min:8|confirmed',
        ]);

        $user->email = $request->email;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }

}
