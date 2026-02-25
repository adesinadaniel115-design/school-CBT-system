<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset link to email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'We could not find this email in our system.',
        ]);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate reset token
        $token = Str::random(60);

        // Store reset token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Build reset link
        $resetLink = route('password.reset', ['email' => $request->email, 'token' => $token]);

        // Store credentials for manual use (since no mail config)
        session([
            'reset_token_' . $request->email => $token,
            'reset_token_link_' . $request->email => $resetLink,
        ]);

        return back()->with('status', 'If an account exists with this email, you will receive a password reset link. Check the table below to access your reset link.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm($email, $token)
    {
        // Check if token exists and is valid (created within last 24 hours)
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return redirect('/login')->with('error', 'Invalid or expired reset token.');
        }

        // Check if token is not older than 24 hours
        if (now()->diffInMinutes($record->created_at) > 1440) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect('/login')->with('error', 'Reset token has expired. Please request a new one.');
        }

        return view('auth.reset-password', compact('email', 'token'));
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $email = $request->email;
        $token = $request->token;

        // Verify token
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return back()->with('error', 'Invalid reset token.');
        }

        // Check if token is not older than 24 hours
        if (now()->diffInMinutes($record->created_at) > 1440) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->with('error', 'Reset token has expired.');
        }

        // Update password
        $user = User::where('email', $email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete reset token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect('/login')->with('status', 'Password reset successfully! You can now login with your new password.');
    }
}
