<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send a password reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['user_email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('user_email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }

    /**
     * Handle reset password form submission.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('user_email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->user_password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }
}
