<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;

Route::get(
    '/slots/availability',
    [AvailabilityController::class, 'index']
);