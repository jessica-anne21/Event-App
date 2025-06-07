<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Manajemen User (Finance & Committee)
    public function indexUsers()
    {
        $users = User::whereIn('role', ['finance', 'committee'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUserForm()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:finance,committee',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUserForm(User $user)
    {
        if (!in_array($user->role, ['finance', 'committee'])) {
            abort(403, 'Anda hanya bisa mengelola user Finance atau Committee.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        if (!in_array($user->role, ['finance', 'committee'])) {
            abort(403, 'Anda hanya bisa mengelola user Finance atau Committee.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:finance,committee',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diubah.');
    }

    public function deleteUser(User $user)
    {
        if (!in_array($user->role, ['finance', 'committee'])) {
            abort(403, 'Anda hanya bisa menghapus user Finance atau Committee.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleUserActive(User $user)
    {
        if (!in_array($user->role, ['finance', 'committee'])) {
            abort(403, 'Anda hanya bisa menonaktifkan user Finance atau Committee.');
        }
        $user->is_active = !$user->is_active;
        $user->save();
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users.index')->with('success', "Pengguna {$user->name} berhasil {$status}.");
    }
}