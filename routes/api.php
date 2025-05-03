<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', App\Http\Controllers\Auth\RegisterController::class);
