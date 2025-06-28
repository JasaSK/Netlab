<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // ← set ke true agar bisa digunakan
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:50|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|in:super_admin,admin,user',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Role',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama tidak boleh kurang dari 3 karakter.',
            'name.max' => 'Nama tidak boleh lebih dari 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.in' => 'Role harus salah satu dari: super_admin, admin, user.',
        ];
    }
}
