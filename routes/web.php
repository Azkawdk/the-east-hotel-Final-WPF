<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminRoomTypeController;
use App\Http\Controllers\Admin\AdminAmenityController;
use App\Http\Controllers\Admin\AdminGuestController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

// ─── Halaman publik (Guest Facing React Frontend) ─────────────
Route::get('/', fn () => view('app'))->name('home');
Route::get('/rooms', fn () => view('app'))->name('rooms');
Route::get('/booking', fn () => view('app'))->name('booking');
Route::get('/lookup', fn () => view('app'))->name('lookup');
Route::get('/experiences', fn () => view('app'))->name('experiences');

// ─── Auth ────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    // ─── Admin panel (harus login) ───────────────────────────
    Route::middleware(['auth'])->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Reservasi
        Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservations/create', [AdminReservationController::class, 'create'])->name('reservations.create');
        Route::post('reservations', [AdminReservationController::class, 'store'])->name('reservations.store');
        Route::get('reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
        Route::patch('reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
        Route::delete('reservations/{reservation}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');

        // Pembayaran
        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::patch('payments/{payment}', [AdminPaymentController::class, 'update'])->name('payments.update');

        // Tamu
        Route::get('guests', [AdminGuestController::class, 'index'])->name('guests.index');
        Route::get('guests/{guest}', [AdminGuestController::class, 'show'])->name('guests.show');
        Route::get('guests/{guest}/edit', [AdminGuestController::class, 'edit'])->name('guests.edit');
        Route::put('guests/{guest}', [AdminGuestController::class, 'update'])->name('guests.update');
        Route::delete('guests/{guest}', [AdminGuestController::class, 'destroy'])->name('guests.destroy');

        // Data master: semua bisa baca
        Route::get('room-types', [AdminRoomTypeController::class, 'index'])->name('room-types.index');
        Route::get('rooms', [AdminRoomController::class, 'index'])->name('rooms.index');
        Route::get('amenities', [AdminAmenityController::class, 'index'])->name('amenities.index');

        // ─── Khusus Admin ─────────────────────────────────────
        Route::middleware(['role:admin'])->group(function () {
            // Tipe Kamar
            Route::get('room-types/create', [AdminRoomTypeController::class, 'create'])->name('room-types.create');
            Route::post('room-types', [AdminRoomTypeController::class, 'store'])->name('room-types.store');
            Route::get('room-types/{roomType}/edit', [AdminRoomTypeController::class, 'edit'])->name('room-types.edit');
            Route::put('room-types/{roomType}', [AdminRoomTypeController::class, 'update'])->name('room-types.update');
            Route::delete('room-types/{roomType}', [AdminRoomTypeController::class, 'destroy'])->name('room-types.destroy');

            // Kamar
            Route::get('rooms/create', [AdminRoomController::class, 'create'])->name('rooms.create');
            Route::post('rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
            Route::get('rooms/{room}/edit', [AdminRoomController::class, 'edit'])->name('rooms.edit');
            Route::put('rooms/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
            Route::delete('rooms/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

            // Fasilitas
            Route::get('amenities/create', [AdminAmenityController::class, 'create'])->name('amenities.create');
            Route::post('amenities', [AdminAmenityController::class, 'store'])->name('amenities.store');
            Route::get('amenities/{amenity}/edit', [AdminAmenityController::class, 'edit'])->name('amenities.edit');
            Route::put('amenities/{amenity}', [AdminAmenityController::class, 'update'])->name('amenities.update');
            Route::delete('amenities/{amenity}', [AdminAmenityController::class, 'destroy'])->name('amenities.destroy');

            // Pengguna
            Route::resource('users', AdminUserController::class)->except(['show']);
        });
    });
});
