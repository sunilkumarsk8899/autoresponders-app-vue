<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function showRegistrationForm(){
        $users = User::get();

        $settings = [
            'title' => 'Register'
        ];

        return Inertia::render('Admin/AdminRegister', [
            'settings' => $settings,
            'users' => $users
        ]);
    }

    public function register_store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $request->all()
        ]);
    }

    /** search user */
    public function user_search(Request $request){
        $query = $request->query('query');
        $users = User::where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->get();
        return response()->json([
            'users' => $users
        ]);
    }

    /** delete user */
    public function delete_user($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User  not found'
            ], 404); // Return a 404 Not Found status code
        }

        $user->delete();

        return response()->json([
            'message' => 'User  deleted successfully'
        ],200);
    }


    public function editUser($id){
        $user = User::find($id);

        $settings = [
            'title' => 'Register'
        ];

        return Inertia::render('Admin/EditUser', [
            'settings' => $settings,
            'user' => $user
        ]);
    }

}
