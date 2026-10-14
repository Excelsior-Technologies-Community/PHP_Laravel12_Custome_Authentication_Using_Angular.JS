<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::checkAndAddColumns();

        // 1. Default Super Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@1234'),
                'role' => 'Admin',
                'status' => 'Active',
                'avatar' => 'https://ui-avatars.com/api/?name=System+Admin&background=4f46e5&color=fff&bold=true',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ]
        );

        // 2. Sample Users across the past 7 days to populate registration trends
        $sampleUsers = [
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul@example.com',
                'role' => 'User',
                'status' => 'Active',
                'days_ago' => 5,
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya@example.com',
                'role' => 'User',
                'status' => 'Active',
                'days_ago' => 4,
            ],
            [
                'name' => 'Amit Verma',
                'email' => 'amit@example.com',
                'role' => 'User',
                'status' => 'Suspended',
                'days_ago' => 3,
            ],
            [
                'name' => 'Neha Joshi',
                'email' => 'neha@example.com',
                'role' => 'User',
                'status' => 'Active',
                'days_ago' => 2,
            ],
            [
                'name' => 'Karan Mehta',
                'email' => 'karan@example.com',
                'role' => 'User',
                'status' => 'Active',
                'days_ago' => 1,
            ],
            [
                'name' => 'Divya Shah',
                'email' => 'divya@example.com',
                'role' => 'User',
                'status' => 'Active',
                'days_ago' => 0,
            ],
        ];

        foreach ($sampleUsers as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('User@1234'),
                    'role' => $u['role'],
                    'status' => $u['status'],
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($u['name']) . '&background=0ea5e9&color=fff&bold=true',
                    'created_at' => Carbon::now()->subDays($u['days_ago']),
                    'updated_at' => Carbon::now()->subDays($u['days_ago']),
                ]
            );
        }
    }
}
