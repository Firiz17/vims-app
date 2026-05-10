<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\PdiInspection;
use App\Models\VehicleStatusLog;

class TechnicianController extends Controller
{
    // 1. Show the Technician Dashboard (Pending PDIs)
    public function index()
    {
        // Fetch only vehicles that are waiting for an inspection
        $vehicles = Vehicle::where('status', 'Pending PDI')->with('location')->get();
        return view('technician.dashboard', compact('vehicles'));
    }

    // 2. Show the PDI Checklist Form
    public function createPdi(Vehicle $vehicle)
    {
        return view('technician.pdi-form', compact('vehicle'));
    }

    // 3. Save the PDI Inspection
    public function storePdi(Request $request, \App\Models\Vehicle $vehicle)
    {
        // 1. THE LOGIC ENGINE: Check if all 4 mandatory items are checked
        $passedMandatory = $request->has('oil_level') &&
                           $request->has('coolant') &&
                           $request->has('interior') &&
                           $request->has('tyre');

        // 2. Determine the automatic status
        if ($passedMandatory) {
            $newStatus = 'Ready for Delivery';
            $message = 'PDI Passed! Vehicle is now Ready for Delivery.';
        } else {
            $newStatus = 'Pending PDI';
            $message = 'PDI Incomplete. Vehicle remains in Pending status until all mandatory checks are complete.';
        }

        // 3. Update the vehicle in the database
        $vehicle->update([
            'status' => $newStatus,
        ]);

        // 4. Log the status change so the Supervisor can track it
        \App\Models\VehicleStatusLog::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
            'status' => $newStatus,
        ]);

        // 5. Send the technician back to their dashboard with the dynamic message
        return redirect()->route('technician.dashboard')->with('success', $message);
    }
}
