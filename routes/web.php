<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('yardstaff')->group(function () {
    Route::get('/check-in', [VehicleController::class, 'create']);
    Route::post('/check-in', [VehicleController::class, 'store'])->name('vehicle.store');
});
});


