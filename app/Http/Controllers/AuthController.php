<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }

    public function login_proses(Request $request)
    {
        $login = $request->login;
        $password = $request->password;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([
            $field => $login,
            'password' => $password
        ])) {
            $request->session()->regenerate();

            $user = Auth::user();

            
            $user->update([
                'status_aktif' => 0
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Login berhasil, selamat datang ' . $user->username);
            }

             if ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard')
                    ->with('success', 'Login berhasil, selamat datang ' . $user->username);
            }

             if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard')
                    ->with('success', 'Login berhasil, selamat datang ' . $user->username);
            }

            Auth::logout();
            return back()->with('error', 'Role tidak dikenali');
        }

        return back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->update([
                'status_aktif' => 0
            ]);
        }

        Auth::logout();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout');
    }
}
