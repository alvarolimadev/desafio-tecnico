<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;

Route::apiResource('users', UserController::class);
Route::get('/profiles', [ProfileController::class, 'index']);