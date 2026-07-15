<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/profile/{id}', [AuthController::class, 'profile']);

Route::post('/change-password', [AuthController::class, 'changePassword']);