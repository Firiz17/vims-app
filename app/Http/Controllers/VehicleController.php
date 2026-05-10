<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Location;

class VehicleController extends Controller
{
    // 1. Show the Check-In Form
    public function create()
    {
        // Fetch locations and count parked vehicles (ignoring delivered ones)
        $locations = \App\Models\Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        return view('yardstaff.check-in', compact('locations'));
    }

    // 2. Save the Vehicle to the Database
    public function store(Request $request)
    {
        // Validate the incoming data so it doesn't crash the database
        $request->validate([
            'vin' => 'required|unique:vehicles',
            'model' => 'required',
            'principal' => 'required',
            'arrival_date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        // Create the vehicle and save it to a variable so we can grab its ID
        $vehicle = Vehicle::create([
            'vin' => $request->vin,
            'model' => $request->model,
            'principal' => $request->principal,
            'status' => 'Checked-In',
            'arrival_date' => $request->arrival_date,
            'location_id' => $request->location_id,
        ]);

        // LOG THE INITIAL STATUS
        \App\Models\VehicleStatusLog::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(), // Logs who checked it in
            'status' => 'Checked-In',
        ]);

        // Send them back to the form with a success message
        return redirect()->route('yardstaff.inventory')->with('success', 'Vehicle checked in successfully!');
    }
    // 3. Show the Inventory List
    public function index()
    {
        // Fetch all vehicles and include their location data to prevent N+1 query issues
        $vehicles = Vehicle::with('location')->get();

        return view('yardstaff.inventory', compact('vehicles'));
    }
    // 4. Show the Edit Form
    public function edit(Vehicle $vehicle)
    {
        // Do the exact same thing for the edit page
        $locations = \App\Models\Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        return view('yardstaff.edit', compact('vehicle', 'locations'));
    }

    // 5. Update Location and Log Damage
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|string',
            'damage_description' => 'nullable|string',
            'damage_severity' => 'nullable|in:Low,Medium,High',
        ]);
        // CHECK IF STATUS IS DIFFERENT BEFORE UPDATING
        if ($vehicle->status !== $request->status) {
            \App\Models\VehicleStatusLog::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => auth()->id(),
                'status' => $request->status,
            ]);
        // UC03: Update the vehicle's location
        $vehicle->update([
            'location_id' => $request->location_id,
            'status' => $request->status,
        ]);



        // UC04: Log Damage if the user typed something in the description box
        if ($request->filled('damage_description')) {
            \App\Models\DamageReport::create([
                'description' => $request->damage_description,
                'severity' => $request->damage_severity ?? 'Low',
                'reported_date' => now(),
                'vehicle_id' => $vehicle->id,
                'user_id' => auth()->id(), // Grabs the logged-in Yard Staff's ID
            ]);
        }

        // Send them back to the inventory list with a success message
        return redirect()->route('inventory.index')->with('success', 'Vehicle updated successfully!');
    }
}
}
