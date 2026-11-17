<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('reservations:expire', function () {
    $n = app(\App\Services\ReservationService::class)->expirePending();
    $this->info("$n reservasi kedaluwarsa dibatalkan.");
})->purpose('Batalkan reservasi pending yang melewati batas pembayaran');

Schedule::command('reservations:expire')->everyFiveMinutes();
