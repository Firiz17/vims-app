<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\SupervisorController;

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
    // Inventory List
    Route::get('/inventory', [VehicleController::class, 'index'])->name('inventory.index');

    Route::get('/check-in', [VehicleController::class, 'create']);
    Route::post('/check-in', [VehicleController::class, 'store'])->name('vehicle.store');

    // --- NEW EDIT ROUTES ---
        Route::get('/vehicle/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
        Route::put('/vehicle/{vehicle}', [VehicleController::class, 'update'])->name('vehicle.update');
});
// --- TECHNICIAN ROUTES ---
    Route::prefix('technician')->group(function () {
        // View vehicles that need inspection
        Route::get('/dashboard', [TechnicianController::class, 'index'])->name('technician.dashboard');

        // Show the actual PDI checklist form for a specific vehicle
        Route::get('/vehicle/{vehicle}/pdi', [TechnicianController::class, 'createPdi'])->name('technician.pdi.create');

        // Save the PDI form to the database
        Route::post('/vehicle/{vehicle}/pdi', [TechnicianController::class, 'storePdi'])->name('technician.pdi.store');
    });
    // --- SUPERVISOR ROUTES ---
    Route::prefix('supervisor')->group(function () {
        // Master Overview Dashboard
        Route::get('/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
        Route::get('/locations', [SupervisorController::class, 'manageLocations'])->name('supervisor.locations');
        Route::post('/locations', [SupervisorController::class, 'storeLocation'])->name('supervisor.locations.store');
        Route::get('/locations/{location}/edit', [SupervisorController::class, 'editLocation'])->name('supervisor.locations.edit');
        Route::put('/locations/{location}', [SupervisorController::class, 'updateLocation'])->name('supervisor.locations.update');
    });
});


