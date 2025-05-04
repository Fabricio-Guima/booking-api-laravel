<?php

use App\Http\Controllers\Auth\{
    LoginController, RegisterController
};
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\User\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', LoginController::class);
Route::post('auth/register', RegisterController::class);

Route::middleware(['auth:sanctum', 'check-permission-of-user-middleware'])->group(function() {

    Route::get('owner/properties',[PropertyController::class, 'index']);
    Route::post('owner/properties', [PropertyController::class, 'store']);

    Route::get('user/bookings',[BookingController::class, 'index']);
});
