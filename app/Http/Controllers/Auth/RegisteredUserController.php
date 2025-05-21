<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => ['required', 'integer', 'unique:users,id_user'],
            'nim' => ['nullable', 'string', 'max:9', 'exists:mahasiswa,nim'],
            'kode_dosen' => ['nullable', 'string', 'max:6', 'exists:dosen,kode_dosen'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'id_user' => $request->id_user,
            'nim' => $request->nim,
            'kode_dosen' => $request->kode_dosen,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('welcome');
    }
}