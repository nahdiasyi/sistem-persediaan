<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Tampilkan form login
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil user dari database
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['error' => 'User tidak ditemukan.']);
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Password salah.']);
        }

        // Login pakai Auth
        Auth::login($user); // inilah yang mengaktifkan session auth Laravel

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        // Hapus session yang benar
        Session::forget('user');

        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
