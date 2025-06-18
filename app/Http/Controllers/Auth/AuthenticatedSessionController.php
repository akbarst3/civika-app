<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba login dengan email atau nim
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';
        $credentials = [
            $field => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->hasRole('mahasiswa')) {
                return redirect()->route('dashboard-pengaju');
            } elseif ($user->hasRole('dosen')) {
                return redirect()->route('dashboard-reviewer1');
            } else {
                return redirect()->route('dashboard-reviewer1');
            }
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
