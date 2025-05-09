<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{

    public function register(Request $request){
        $request->validate([
            'user_fname' => ['required', 'string', 'max:255'],
            'user_mname' => ['nullable', 'string', 'max:255'],
            'user_lname' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:users,user_email'],
            'user_password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:user_password'],
        ]);

        $user = User::create([
            'user_fname' => $request->user_fname,
            'user_mname' => $request->user_mname,
            'user_lname' => $request->user_lname,
            'user_email' => $request->user_email,
            'user_contact_number' => $request->user_contact_number,
            'user_username' => $request->user_username,
            'user_password' => Hash::make($request->user_password),
            'role_id' => 2, 
            'status_id' => 1, // Active
        ]);

        return response()->json(['message' => 'User created successfully!', 'user' => $user]);
    }

    public function login(Request $request){
        $request->validate([
            'user_email' => ['required', 'email'],
            'user_password' => ['required', 'string'],
        ]);

        $user = User::where('user_email', $request->user_email)->first();

        if(!$user || !Hash::check($request->user_password, $user->user_password)){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'User logged in successfully!', 'user' => $user, 'token' => $token]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully!']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->user_password)) {
            return response()->json(['message' => 'Current password is incorrect'], 403);
        }

        $user->user_password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}
