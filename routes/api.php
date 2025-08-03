<?php

use App\Http\Controllers\Api\BookingTransactionController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\OfficeSpaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// nama cek api ini harus sma dengan yg kita daftarin di app.php pada folder bbotrsap
Route::middleware('cek_api')->group(
    function () {
        Route::get('/city/{city:slug}', [CityController::class, 'show']);
        Route::apiResource('/cities', CityController::class);

        Route::get('/office/{officeSpace:slug}', [OfficeSpaceController::class, 'show']);
        Route::apiResource('/offices', OfficeSpaceController::class);

        Route::post('/booking-transaction', [BookingTransactionController::class, 'store']);
        Route::get('/booking-transaction', [BookingTransactionController::class, 'store']);
        Route::post('/check-booking', [BookingTransactionController::class, 'booking-details']);
    }
);


// itu ad dua  cara membuat endpoint bis manual menggunakan get dan otomati mengunalan apiresoource
// store untuk menyimpan data 