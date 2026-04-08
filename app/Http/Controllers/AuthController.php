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
            'password' => $password,
            'status_aktif' => 1
        ])) {
            $request->session()->regenerate();

            $user = Auth::user(); // ambil user yang login
            $role = $user->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome, ' . $user->name . '!');
            }

            // Tambahkan role lain di sini
            // if ($role === 'petugas') ...
            // if ($role === 'owner') ...

            Auth::logout();
            return back()->with('error', 'Role tidak dikenali');
        }

        return back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
