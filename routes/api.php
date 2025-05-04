<?php

use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\User\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', App\Http\Controllers\Auth\RegisterController::class);

Route::middleware(['auth:sanctum', 'check-permission-of-user-middleware'])->group(function() {

    Route::get('owner/properties',
        [PropertyController::class, 'index']);
    Route::get('user/bookings',
        [BookingController::class, 'index']);
});
