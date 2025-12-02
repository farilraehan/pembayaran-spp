<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)
                    ->where('password', $request->password)
                    ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }

        Auth::login($user);

        return redirect('/app/dashboard')->with(
            'success',
            'Selamat datang ' . ($user->nama ?? $user->name ?? 'Pengguna')
        );
    }


    public function logout()
{
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/')->with('success', 'Anda telah berhasil keluar');
}

}
