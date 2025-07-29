<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('web')->get('/user', [UserController::class, 'getLoggedInUser']);
Route::middleware('web')->get('/users/index', [UserController::class, 'index']);
Route::middleware('web')->get('/users/show/{user}', [UserController::class, 'show']);
Route::middleware('web')->post('/users/update/{user}', [UserController::class, 'update']);
Route::middleware('web')->post('/users/delete/{user}', [UserController::class, 'delete']);
Route::middleware('web')->post('/users/create-user', [UserController::class, 'create']);
Route::middleware('web')->post('/users/reset-password', [UserController::class, 'resetPassword']);