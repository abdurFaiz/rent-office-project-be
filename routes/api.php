<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\OfficeSpaceController;
use App\Http\Controllers\Api\BookingTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api_key')->group(function () {

    // sedangkan route ini hanya membackup method show dan biasanya digunakan untuk task spesifik
    Route::get('/city/{city:slug}', [CityController::class, 'show']);

    // api resource ini sudah membackup semua method yg ada paada controller
    Route::apiResource('/cities', CityController::class);

    Route::get('/office/{officeSpace:slug}', [OfficeSpaceController::class, 'show']);
    Route::apiResource('/offices', OfficeSpaceController::class);

    Route::post('/booking-transaction', [BookingTransactionController::class, 'store']);
    Route::post('/check-booking', [BookingTransactionController::class, 'booking_details']);
});
