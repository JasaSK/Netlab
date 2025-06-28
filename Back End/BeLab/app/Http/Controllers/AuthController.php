<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user'
        ]);
        $user->sendEmailVerificationNotification();
        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['Email belum terdaftar.'],
            ]);
        }

        // Password salah
        if (! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Password salah.'],
            ]);
        }

        //  Email belum diverifikasi
        if (! $user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Email belum diverifikasi.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    // ✅ Ambil data user yang sedang login
    public function getUser(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    // ✅ Update data user
    public function updateUser(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'role' => 'sometimes|in:super_admin,admin,user'
        ]);

        if ($request->filled('name')) $user->name = $request->name;
        if ($request->filled('email')) $user->email = $request->email;
        if ($request->filled('password')) $user->password = Hash::make($request->password);
        if ($request->filled('role')) $user->role = $request->role;

        $user->save();

        return response()->json([
            'message' => 'Data user berhasil diperbarui',
            'user' => $user
        ]);
    }

    // ✅ Hapus user
    public function deleteUser(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Hapus semua token
        $user->delete();

        return response()->json([
            'message' => 'Akun user berhasil dihapus'
        ]);
    }
}
