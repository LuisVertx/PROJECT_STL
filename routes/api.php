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