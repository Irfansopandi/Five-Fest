<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($otp), 'created_at' => now()]
        );

        Mail::send('emails.otp', ['otp' => $otp], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Kode OTP Reset Password - FiveFest');
        });

        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric|digits:6']);
        $email = session('reset_email');

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau tidak valid.']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('password.reset.form')->with('success', 'OTP valid. Silakan buat password baru.');
    }

    public function showResetForm()
    {
        if (!session('otp_verified') || !session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }
}
