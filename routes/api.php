<?php
use App\Http\Controllers\HoldController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;

Route::get(
    '/slots/availability',
    [AvailabilityController::class, 'index']
);

Route::post(
    '/slots/{slot}/hold',
    [HoldController::class, 'store']
);

Route::post(
    '/holds/{hold}/confirm',
    [HoldController::class, 'confirm']
);

Route::delete(
    '/holds/{hold}',
    [HoldController::class, 'destroy']
);