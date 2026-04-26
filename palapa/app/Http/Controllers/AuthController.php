<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Menampilkan halaman form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Memproses data pendaftaran
    public function register(Request $request)
    {
        // 1. Validasi inputan form
        $validated = $request->validate([
            'users_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Simpan user baru ke database
        $user = new User();
        $user->users_name = $validated['users_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->role = 'masyarakat';
        $user->password = Hash::make($validated['password']);

        if (! $user->save()) {
            throw ValidationException::withMessages([
                'email' => 'Registrasi gagal disimpan. Silakan coba lagi.',
            ]);
        }

        // 3. Otomatis login setelah berhasil daftar
        Auth::login($user);

        // 4. Arahkan ke halaman beranda
        return redirect()->route('beranda')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/beranda')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda telah berhasil logout!');
    }
}