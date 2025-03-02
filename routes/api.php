<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/city/{city:slug}', [CityController::class, 'show']);
Route::apiResource('/cities', [CityController::class]);

Route::get('/office/{officeSpace:slug}', [OfficeSpaceController::class, 'show']);
Route::apiResource('/offices', [OfficeSpaceController::class]);

Route::get('/booking-transaction', [BookingTransactionController::class, 'store']);
Route::apiResource('/check-booking', [BookingTransactionController::class, 'booking_details']);