<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUsers(){
        $users = User::with('role', 'status')->get();

        return response()->json(['users' => $users]);
    }

    public function addUser(Request $request){
        $request->validate([
            'user_fname' => ['required', 'string', 'max:255'],
            'user_mname' => ['nullable', 'string', 'max:255'],
            'user_lname' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:users,user_email'],
            'user_username' => ['required', 'string', 'max:255', 'unique:users,user_username'],
            'user_password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'status_id' => ['required', 'exists:statuses,id'],
        ]);

        $user = User::create([
            'user_fname' => $request->user_fname,
            'user_mname' => $request->user_mname,
            'user_lname' => $request->user_lname,
            'user_email' => $request->user_email,
            'user_username' => $request->user_username,
            'user_password' => Hash::make($request->user_password),
            'role_id' => $request->role_id,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'User successfully created!', 'user' => $user]);
    }

    public function editUser(Request $request, $id){
        $request->validate([
            'user_fname' => ['required', 'string', 'max:255'],
            'user_mname' => ['nullable', 'string', 'max:255'],
            'user_lname' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:users,user_email,' . $id],
            'user_username' => ['required', 'string', 'max:255', 'unique:users,user_username,' . $id],
            'role_id' => ['required', 'exists:roles,id'],
            'status_id' => ['required', 'exists:statuses,id'],
        ]);

        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'User not found!'], 404);
        }

        $user->update([
            'user_fname' => $request->user_fname,
            'user_mname' => $request->user_mname,
            'user_lname' => $request->user_lname,
            'user_email' => $request->user_email,
            'user_username' => $request->user_username,
            'role_id' => $request->role_id,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'User successfully edited!', 'user' => $user]);
    }

    public function deleteUser($id){
        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'User not found!'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User successfully deleted!']);
    }
}
