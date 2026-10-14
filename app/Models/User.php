<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    public static function checkAndAddColumns()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role', 30)->default('User')->after('password');
                }
                if (!Schema::hasColumn('users', 'status')) {
                    $table->string('status', 20)->default('Active')->after('role');
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('status');
                }
                if (!Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('avatar');
                }
            });
        }
    }
}
