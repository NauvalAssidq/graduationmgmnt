<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    // Daftar semua admin dengan pencarian
    public function index(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->paginate(10)->withQueryString();

        return view('admin.admin_management.index', compact('admins'));
    }

    // Form tambah admin baru
    public function create()
    {
        return view('admin.admin_management.create');
    }

    // Simpan admin baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'nip'      => 'nullable|string|size:18|unique:admin,nip',
            'email'    => 'required|email|unique:admin,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name'     => $request->name,
            'nip'      => $request->nip ?: null,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('kelola-admin.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
    }

    // Form edit admin
    public function edit(Admin $admin_management)
    {
        return view('admin.admin_management.edit', ['admin' => $admin_management]);
    }

    // Update data admin
    public function update(Request $request, Admin $admin_management)
    {
        $admin = $admin_management;

        $request->validate([
            'name'         => 'required|string|max:100',
            'nip'          => 'nullable|string|size:18|unique:admin,nip,' . $admin->id,
            'email'        => 'required|email|unique:admin,email,' . $admin->id,
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name  = $request->name;
        $admin->nip   = $request->nip ?: null;
        $admin->email = $request->email;

        if ($request->filled('new_password')) {
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('kelola-admin.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    // Hapus admin (tidak bisa hapus diri sendiri)
    public function destroy(Admin $admin_management)
    {
        $admin = $admin_management;

        if ($admin->id === Auth::id()) {
            return redirect()->route('kelola-admin.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return redirect()->route('kelola-admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
