<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        return User::orderBy('name')->get(['id', 'name', 'email', 'role', 'is_active']);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff',
        ]);

        return response()->json(User::create($d)->only(['id', 'name', 'email', 'role', 'is_active']), 201);
    }

    public function update(Request $request, User $user)
    {
        $d = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,staff',
            'is_active' => 'boolean',
        ]);
        if (empty($d['password'])) {
            unset($d['password']);
        }
        if ($user->id === $request->user()->id && (($d['role'] ?? 'admin') !== 'admin' || ($d['is_active'] ?? true) === false)) {
            throw ValidationException::withMessages(['role' => 'Anda tidak dapat menurunkan atau menonaktifkan akun sendiri.']);
        }
        $user->update($d);

        return $user->only(['id', 'name', 'email', 'role', 'is_active']);
    }

    /** Akun tidak dihapus permanen, hanya dinonaktifkan agar jejak pemrosesan tetap ada. */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            throw ValidationException::withMessages(['user' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
        }
        $user->update(['is_active' => false]);
        $user->tokens()->delete();

        return response()->noContent();
    }
}
