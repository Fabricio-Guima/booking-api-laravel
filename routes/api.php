<?php

use App\Http\Controllers\Auth\{
    LoginController, RegisterController
};
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\PropertyPhotoController;
use App\Http\Controllers\Public\ApartmentController;
use App\Http\Controllers\Public\PropertyController as PublicPropertyController;
use App\Http\Controllers\Public\PropertySearchController;
use App\Http\Controllers\User\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', LoginController::class);
Route::post('auth/register', RegisterController::class);

Route::middleware(['auth:sanctum', 'check-permission-of-user-middleware'])->group(function() {


    Route::prefix('owner')->group(function () {
        Route::get('properties',[PropertyController::class, 'index']);
        Route::post('properties', [PropertyController::class, 'store']);

        Route::post('properties/{property}/photos', [PropertyPhotoController::class, 'store']);
        Route::post('properties/{property}/photos/{photo}/reorder/{newPosition}', [PropertyPhotoController::class, 'reorder']);
    });

    Route::get('user/bookings',[BookingController::class, 'index']);
});

Route::get('search',PropertySearchController::class);
Route::get('properties/{property}', PublicPropertyController::class);
Route::get('apartments/{apartment}', ApartmentController::class);


