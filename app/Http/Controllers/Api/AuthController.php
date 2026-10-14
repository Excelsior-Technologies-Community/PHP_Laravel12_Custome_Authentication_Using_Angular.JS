<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ===============================
    // Register
    // ===============================
    public function register(Request $request)
    {
        User::checkAndAddColumns();

        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $userCount = User::count();
        $role = ($userCount === 0) ? 'Admin' : 'User';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'status' => 'Active',
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=0d6efd&color=fff',
            'last_login_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully. Please login.',
            'user' => $user
        ]);
    }

    // ===============================
    // Login
    // ===============================
    public function login(Request $request)
    {
        User::checkAndAddColumns();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password combination.'
            ], 401);
        }

        if ($user->status === 'Suspended') {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been suspended. Please contact administrator.'
            ], 403);
        }

        $user->update(['last_login_at' => now()]);

        // Simulated JWT/Auth token for client SPA Session management
        $token = 'auth_token_' . bin2hex(random_bytes(24));

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user
        ]);
    }

    // ===============================
    // User Profile
    // ===============================
    public function profile($id)
    {
        User::checkAndAddColumns();
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
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
            ], 404);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.'
        ]);
    }

    // =======================================
    // Dashboard Statistics + Chart.js Analytics
    // =======================================
    public function dashboard()
    {
        User::checkAndAddColumns();

        $totalUsers = User::count();
        $todayUsers = User::whereDate('created_at', today())->count();
        $activeUsers = User::where('status', 'Active')->count();
        $suspendedUsers = User::where('status', 'Suspended')->count();
        $adminCount = User::where('role', 'Admin')->count();
        $userRoleCount = User::where('role', '!=', 'Admin')->count();

        // 7-day registration velocity chart data
        $registrationTrend = [];
        $trendLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = now()->subDays($i)->format('d M');
            $registrationTrend[] = User::whereDate('created_at', $date)->count();
        }

        return response()->json([
            'status' => true,
            'total_users' => $totalUsers,
            'today_users' => $todayUsers,
            'active_users' => $activeUsers,
            'suspended_users' => $suspendedUsers,
            'admin_count' => $adminCount,
            'user_count' => $userRoleCount,
            'latest_user' => User::latest()->first(),
            'chart_data' => [
                'labels' => $trendLabels,
                'trend' => $registrationTrend,
                'roles' => [
                    'Admin' => $adminCount,
                    'Standard Users' => $userRoleCount,
                ],
                'statuses' => [
                    'Active' => $activeUsers,
                    'Suspended' => $suspendedUsers,
                ]
            ]
        ]);
    }

    // =======================================
    // User List with Search, Status Filter & Pagination
    // =======================================
    public function users(Request $request)
    {
        User::checkAndAddColumns();

        $query = User::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest('id')->paginate($request->get('per_page', 5));

        return response()->json($users);
    }

    // ===============================
    // Toggle User Status (Active / Suspended)
    // ===============================
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = ($user->status === 'Active') ? 'Suspended' : 'Active';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => "User status updated to {$user->status}.",
            'user' => $user
        ]);
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
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
