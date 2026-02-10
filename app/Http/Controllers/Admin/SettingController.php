<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $apiUrl = Setting::where('key', 'wisudawan_api_url')->value('value');
        return view('admin.settings.index', compact('apiUrl'));
    }

    // fungsi update berguna untuk mengganti data yang ada di database dalam pengaturan
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'wisudawan_api_url' => 'nullable|url',
        ]);

        // Update Account Settings
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Update Integration Settings
        Setting::updateOrCreate(
            ['key' => 'wisudawan_api_url'],
            ['value' => $request->wisudawan_api_url]
        );

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
