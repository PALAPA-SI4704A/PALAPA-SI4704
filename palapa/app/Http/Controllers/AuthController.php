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
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        // 2. Simpan user baru ke database
        $user = new User();
        $user->users_name = $validated['users_name'];
        $user->email = $validated['email'] ?? null;
        $user->phone = $validated['phone'];
        $user->role = 'masyarakat'; // Secara default yang register mandiri adalah masyarakat
        $user->password = Hash::make($validated['password']);

        if (! $user->save()) {
            throw ValidationException::withMessages([
                'phone' => 'Registrasi gagal disimpan. Silakan coba lagi.',
            ]);
        }

        // 3. Otomatis login setelah berhasil daftar
        Auth::login($user);

        // 4. Arahkan ke halaman beranda warga
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
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $fieldType => $login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Pengecekan role pengguna untuk menentukan halaman tujuan
            $role = Auth::user()->role;

            if ($role === 'petugas') {
                return redirect()->route('petugas.dashboard')->with('success', 'Login berhasil sebagai petugas!');
            } elseif ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil sebagai admin!');
            }

            // Default redirect untuk Warga (Masyarakat)
            return redirect()->route('beranda')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'login' => 'Email/Nomor Telepon atau password yang dimasukkan salah.',
        ])->onlyInput('login');
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