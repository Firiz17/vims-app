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
    public function storePdi(Request $request, Vehicle $vehicle)
    {
        // 1. Your new validation rules
        $request->validate([
            'tyre' => 'required|in:Checked,Not Checked',
            'oil_level' => 'required|in:Checked,Not Checked',
            'coolant' => 'required|in:Checked,Not Checked',
            'interior_cleanliness' => 'required|in:Checked,Not Checked',
            'tinted_window' => 'required|in:Applied,Not Applied',
            'dashcam' => 'required|in:Applied,Not Applied',
        ]);

        // 2. AUTOMATICALLY CALCULATE THE RESULT
        // If ANY item is left unchecked or unapplied, it is not ready for delivery.
        $hasUncheckedItems = (
            $request->tyre === 'Not Checked' ||
            $request->oil_level === 'Not Checked' ||
            $request->coolant === 'Not Checked' ||
            $request->interior_cleanliness === 'Not Checked' ||
            $request->tinted_window === 'Not Applied' ||
            $request->dashcam === 'Not Applied'
        );

        // Set variables based on the new calculation
        $calculatedResult = $hasUncheckedItems ? 'Fail' : 'Pass';
        $newStatus = $hasUncheckedItems ? 'Pending PDI' : 'Ready for Delivery';

        // 3. Save the inspection to the database
        PdiInspection::create([
            'tyre' => $request->tyre === 'Checked' ? 1 : 0,
            'oil_level' => $request->oil_level === 'Checked' ? 1 : 0,
            'coolant' => $request->coolant === 'Checked' ? 1 : 0,
            'interior_cleanliness' => $request->interior_cleanliness === 'Checked' ? 1 : 0,
            'tinted_window' => $request->tinted_window === 'Applied' ? 1 : 0,
            'dashcam' => $request->dashcam === 'Applied' ? 1 : 0,
            'inspection_date' => now(),
            'result' => $calculatedResult,
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
        ]);

        // 4. Log the status change
        VehicleStatusLog::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
            'status' => $newStatus,
        ]);

        // 5. Update the vehicle record
        $vehicle->update(['status' => $newStatus]);

        return redirect()->route('technician.dashboard')->with('success', 'PDI Checklist submitted! Status automatically updated to: ' . $newStatus);
    }
}
