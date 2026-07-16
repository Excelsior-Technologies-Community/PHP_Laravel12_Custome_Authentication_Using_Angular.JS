<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/profile/{id}', [AuthController::class, 'profile']);

Route::post('/change-password', [AuthController::class, 'changePassword']);

// New
Route::get('/dashboard', [AuthController::class, 'dashboard']);

Route::get('/users', [AuthController::class, 'users']);

Route::delete('/users/{id}',[AuthController::class,'deleteUser']);