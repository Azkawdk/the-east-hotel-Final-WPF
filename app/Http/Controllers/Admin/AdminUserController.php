<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,staff',
        ]);

        User::create($d);
        return redirect()->route('admin.users.index')->with('success', "Pengguna '{$d['name']}' berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $d = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'  => 'nullable|string|min:8|confirmed',
            'role'      => 'required|in:admin,staff',
            'is_active' => 'boolean',
        ]);

        if (empty($d['password'])) {
            unset($d['password']);
        }
        $d['is_active'] = $request->boolean('is_active', false);

        if ($user->id === auth()->id() && ($d['role'] !== 'admin' || !$d['is_active'])) {
            throw ValidationException::withMessages(['role' => 'Anda tidak dapat menurunkan atau menonaktifkan akun sendiri.']);
        }

        $user->update($d);
        return redirect()->route('admin.users.index')->with('success', "Pengguna '{$user->name}' berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
        }

        $user->update(['is_active' => false]);
        $user->tokens()->delete();
        return redirect()->route('admin.users.index')->with('success', "Pengguna '{$user->name}' berhasil dinonaktifkan.");
    }
}
