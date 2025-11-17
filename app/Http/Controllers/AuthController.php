<?php

// ini bagian dari file AuthController.php
namespace App\Http\Controllers;

// bagian untuk mengimport class yang dibutuhkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

// bagian dari class AuthController
class AuthController extends Controller
{
    public function showRegister()
    {
        // ini bagian buat menampilkan dari register
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Ini bagian buat logika dari register

        // Validasi data yang akan di minta dibawah kolom. yaitu name dan password
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Membuat user baru di database
        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        // Login user secara otomatis setelah registerasi berhasil
        Auth::login($user);

        // Redirect ke halaman login sesuai dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function showLogin()
    {
        // ini bagian buat menampilkan dari login
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input sesuai di minta sebelumnya
        $credentials = $request->validate([
            // nama dan password harus wajib di isi
            'name' => 'required|string',
            'password' => 'required',
        ]);

        // fungsinya memcoba login menggunakan data $credentials, dengan parameter $request->remember(ingat saya)
        if (Auth::attempt($credentials, $request->remember)) {
            // Regenerasi session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // Redirect ke halaman login sesuai dengan pesan sukses
            return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        // jika gagal, kirim pesan error dan kembali input name
        return back()->withErrors([
            'name' => 'name atau password yang dimasukan salah.',
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Hapus autentikasi user
        $request->session()->invalidate(); // Matikan(off) sesi lama
        $request->session()->regenerateToken(); // Buat token CSRF Baru
        // Arahkan kehalaman utama setelah logout
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}