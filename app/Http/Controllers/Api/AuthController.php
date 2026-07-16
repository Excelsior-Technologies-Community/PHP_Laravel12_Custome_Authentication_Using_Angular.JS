<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===============================
    // Register
    // ===============================
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.'
        ]);
    }

    // ===============================
    // Login
    // ===============================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'user' => $user
        ]);
    }

    // ===============================
    // User Profile
    // ===============================
    public function profile($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ]);
        }

        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }

    // ===============================
    // Change Password
    // ===============================
    public function changePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ]);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.'
        ]);
    }

    // =======================================
    // Dashboard Statistics
    // =======================================
    public function dashboard()
    {
        return response()->json([
            'status' => true,
            'total_users' => User::count(),
            'today_users' => User::whereDate('created_at', today())->count(),
            'latest_user' => User::latest()->first(),
        ]);
    }

    // =======================================
    // User List with Search & Pagination
    // =======================================
    public function users(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
           ->orderBy('id', 'asc')
            ->paginate(5);

        return response()->json($users);
    }

    // ===============================
    // Delete User
    // ===============================
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ]);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
