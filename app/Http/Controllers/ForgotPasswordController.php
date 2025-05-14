<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgotpass');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'siswa_email' => 'required|email|exists:users,siswa_email',
        ]);
        
        // Get the user
        $user = User::where('siswa_email', $request->siswa_email)->first();

        // Send Laravel reset email using default Password Broker
        $status = Password::sendResetLink(
            ['siswa_email' => $request->siswa_email]
        );
        

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link has been sent!')
            : back()->withErrors(['siswa_email' => __($status)]);
    }
}

