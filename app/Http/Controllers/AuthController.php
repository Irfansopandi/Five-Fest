<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ==================== SHOW FORMS ====================

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    // ==================== PROCESS LOGIN ====================

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // ===== VALIDASI MATH CAPTCHA =====
        $num1   = (int) $request->input('captcha_num1');
        $num2   = (int) $request->input('captcha_num2');
        $answer = (int) $request->input('captcha_answer');

        if ($answer !== ($num1 + $num2)) {
            return back()
                ->withErrors(['captcha' => 'Jawaban verifikasi salah. Coba lagi.'])
                ->onlyInput('email');
        }
        // ==================================

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user->update(['last_login' => now()]);

            // ===== LOGIKA INGAT SAYA (PRE-FILL) =====
            if ($remember) {
                // Simpan selama 30 hari (43200 menit)
                \Illuminate\Support\Facades\Cookie::queue('remember_email', $request->email, 43200);
                \Illuminate\Support\Facades\Cookie::queue('remember_password', $request->password, 43200);
            } else {
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('remember_email'));
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('remember_password'));
            }

            return $this->redirectUserByRole($user->role)
                        ->with('success', 'Selamat Datang Kembali, ' . $user->name . '!');
        }

        return back()->withErrors(['password' => 'Kata sandi salah.'])->onlyInput('email');
    }

    // ==================== PROCESS REGISTER ====================

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms'    => 'required|accepted',
        ]);

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'user',
            'last_login' => now(),
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    public function showRegisterVendor()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user()->role);
        }
        return view('auth.register-vendor');
    }

    // KHUSUS DAFTAR JADI VENDOR
    public function registerVendor(Request $request)
    {
        $request->validate([
            // Section 1
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',

            // Section 2
            'document_type' => 'required|in:individu,badan_hukum',
            'npwp_number'   => 'required|string|max:30',
            'npwp_name'     => 'required|string|max:255',
            'npwp_address'  => 'required|string',
            'npwp_file'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // Optional for Badan Hukum
            'nib_number'          => 'required_if:document_type,badan_hukum|nullable|string|max:30',
            'anggaran_dasar_file' => 'required_if:document_type,badan_hukum|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'email.unique'                     => 'Email ini sudah terdaftar sebagai akun lain.',
            'password.confirmed'               => 'Konfirmasi kata sandi tidak cocok.',
            'npwp_file.required'               => 'Dokumen NPWP wajib diunggah.',
            'nib_number.required_if'           => 'Nomor NIB wajib diisi untuk Badan Hukum.',
            'anggaran_dasar_file.required_if'  => 'Dokumen Anggaran Dasar wajib diunggah untuk Badan Hukum.',
        ]);

        $npwpPath = $request->file('npwp_file')->store('vendors/npwp', 'public');

        $adPath = null;
        if ($request->hasFile('anggaran_dasar_file')) {
            $adPath = $request->file('anggaran_dasar_file')->store('vendors/legal', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'vendor',
            'status'   => 'active',
            'verification_status' => 'pending',

            'document_type' => $request->document_type,
            'npwp_number'   => $request->npwp_number,
            'npwp_name'     => $request->npwp_name,
            'npwp_address'  => $request->npwp_address,
            'npwp_file'     => $npwpPath,
            'nib_number'    => $request->nib_number,
            'anggaran_dasar_file' => $adPath,
            'last_login'    => now(),
        ]);

        Auth::login($user);

        return redirect()->route('vendor.dashboard')->with('success', 'Selamat! Pendaftaran vendor berhasil. Akun kamu sedang dalam tahap verifikasi.');
    }

     public function showReapplyVendor()
        {
            $user = Auth::user();
            if ($user->role !== 'vendor' || $user->verification_status !== 'rejected') {
                return redirect()->route('vendor.dashboard');
            }
            return view('auth.register-vendor');
        }

        public function reapplyVendor(Request $request)
    {
        // Ambil fresh dari database, bukan dari cache session
        $user = User::find(Auth::id());

        $request->validate([
            'document_type' => 'required|in:individu,badan_hukum',
            'npwp_number'   => 'required|string|max:30',
            'npwp_name'     => 'required|string|max:255',
            'npwp_address'  => 'required|string',
            'npwp_file'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nib_number'          => 'required_if:document_type,badan_hukum|nullable|string|max:30',
            'anggaran_dasar_file' => 'required_if:document_type,badan_hukum|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $npwpPath = $request->file('npwp_file')->store('vendors/npwp', 'public');

        $adPath = $user->anggaran_dasar_file; // pertahankan file lama kalau tidak upload baru
        if ($request->hasFile('anggaran_dasar_file')) {
            $adPath = $request->file('anggaran_dasar_file')->store('vendors/legal', 'public');
        }

        // Gunakan save() bukan update() untuk lebih aman
        $user->document_type       = $request->document_type;
        $user->npwp_number         = $request->npwp_number;
        $user->npwp_name           = $request->npwp_name;
        $user->npwp_address        = $request->npwp_address;
        $user->npwp_file           = $npwpPath;
        $user->nib_number          = $request->nib_number;
        $user->anggaran_dasar_file = $adPath;
        $user->verification_status = 'pending';
        $user->rejection_reason    = null;
        $user->save();

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Dokumen berhasil dikirim ulang. Akun kamu sedang dalam proses verifikasi kembali.');
    }

    public function upgradeToVendor()
    {
        $user = User::findOrFail(Auth::id());
        $user->update(['role' => 'vendor']);

        return redirect()->route('vendor.dashboard')->with('success', 'Sekarang kamu adalah Vendor.');
    }

    // ==================== HELPERS & LOGOUT ====================

    protected function redirectUserByRole($role)
    {
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'owner') {
            return redirect()->route('owner.dashboard');
        }
        
        if ($role === 'vendor') {
            return redirect()->route('vendor.dashboard');
        }

        if ($role === 'vendor_staff'){
            return redirect()->route('vendor.staff.scanner');
        }


        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar akun.');
    }

    // ==================== GOOGLE AUTH ====================

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                $user->update([
                    'google_id'    => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'last_login'   => now(),
                ]);
            } else {
                $user = User::create([
                    'name'         => $googleUser->getName(),
                    'email'        => $googleUser->getEmail(),
                    'google_id'    => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'password'     => Hash::make(rand(100000, 999999)),
                    'role'         => 'user',
                    'last_login'   => now(),
                ]);
            }

            Auth::login($user);
            return $this->redirectUserByRole($user->role)
                        ->with('success', 'Selamat Datang, ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}