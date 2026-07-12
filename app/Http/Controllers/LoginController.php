<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('login'); // Pastikan nama file blade kamu adalah login.blade.php
    }

    // Proses Login
    public function login(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'g-recaptcha-response.required' => 'Mohon selesaikan verifikasi captcha.',
        ]);

        // 2. Verifikasi Captcha ke Server Google
        // Ganti 'SECRET_KEY_KAMU' dengan Secret Key dari Google Admin Console
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => '6Lf0ytYsAAAAAGaOMJJF0EpKnXgKumaSsqFw_ACK', 
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $captchaResult = $response->json();

        if (!$captchaResult['success']) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.'
            ])->withInput();
        }

        // 3. Proses Authentikasi (Cek Email & Password di Database)
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil login
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang kembali!');
        }

        // Jika gagal login (Email/Password salah)
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->withInput();
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}