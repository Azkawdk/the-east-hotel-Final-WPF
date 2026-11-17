<?php

use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // ---------- Publik (tamu) ----------
    Route::get('room-types', [PublicController::class, 'roomTypes']);
    Route::get('room-types/{slug}', [PublicController::class, 'roomType']);
    Route::get('amenities', [PublicController::class, 'amenities']);
    Route::post('reservations', [PublicController::class, 'storeReservation'])->middleware('throttle:20,1');
    Route::get('reservations/lookup', [PublicController::class, 'lookup'])->middleware('throttle:30,1');
    Route::post('reservations/{code}/payments', [PublicController::class, 'pay'])->middleware('throttle:20,1');

    // ---------- Autentikasi ----------
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        // ---------- Admin + Staf ----------
        Route::prefix('admin')->middleware(RoleMiddleware::class.':admin,staff')->group(function () {
            Route::get('dashboard', [Admin\DashboardController::class, 'index']);

            Route::get('reservations', [Admin\ReservationController::class, 'index']);
            Route::post('reservations', [Admin\ReservationController::class, 'store']);
            Route::get('reservations/{reservation}', [Admin\ReservationController::class, 'show']);
            Route::patch('reservations/{reservation}/status', [Admin\ReservationController::class, 'updateStatus']);

            Route::get('payments', [Admin\PaymentController::class, 'index']);
            Route::patch('payments/{payment}', [Admin\PaymentController::class, 'update']);

            Route::get('guests', [Admin\GuestController::class, 'index']);
            Route::get('guests/{guest}', [Admin\GuestController::class, 'show']);
            Route::put('guests/{guest}', [Admin\GuestController::class, 'update']);

            // Data master: baca boleh untuk staf
            Route::get('room-types', [Admin\RoomTypeController::class, 'index']);
            Route::get('rooms', [Admin\RoomController::class, 'index']);
            Route::get('amenities', [Admin\AmenityController::class, 'index']);
        });

        // ---------- Khusus Admin ----------
        Route::prefix('admin')->middleware(RoleMiddleware::class.':admin')->group(function () {
            Route::apiResource('room-types', Admin\RoomTypeController::class)->except(['index', 'show']);
            Route::apiResource('rooms', Admin\RoomController::class)->except(['index', 'show']);
            Route::apiResource('amenities', Admin\AmenityController::class)->except(['index', 'show']);
            Route::delete('guests/{guest}', [Admin\GuestController::class, 'destroy']);
            Route::get('reports/summary', [Admin\ReportController::class, 'summary']);
            Route::apiResource('users', Admin\UserController::class)->except(['show']);
        });
    });
});
