<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // 1. Show the Check-In Form
    public function create()
    {
        // Fetch all locations AND count how many cars are currently parked in them
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
    public function index(Request $request)
    {
        // 1. Start a query builder
        $query = \App\Models\Vehicle::query();

        // 2. Filter by Search Box (VIN or Model)
        $query->when($request->search, function ($q) use ($request) {
            $q->where('vin', 'like', '%' . $request->search . '%')
              ->orWhere('model', 'like', '%' . $request->search . '%');
        });

        // 3. Filter by Status Dropdown
        $query->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // 4. Filter by Location Dropdown
        $query->when($request->location_id, function ($q) use ($request) {
            $q->where('location_id', $request->location_id);
        });

        // 5. Get the filtered results, newest first
        $vehicles = $query->latest()->get();

        // 6. We also need all locations so we can populate the filter dropdown!
        $locations = \App\Models\Location::all();

        return view('yardstaff.inventory', compact('vehicles', 'locations'));
    }

    // 4. SHOW THE EDIT FORM (This was the missing piece!)
    public function edit(\App\Models\Vehicle $vehicle)
    {
        // Fetch locations and count parked cars
        $locations = \App\Models\Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        // Pass BOTH the specific vehicle and the locations to the view
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
        } // <--- FIXED THE MISSING BRACKET HERE!

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
                'user_id' => auth()->id(),
            ]);
        }

        // FIXED THE ROUTE NAME HERE!
        return redirect()->route('yardstaff.inventory')->with('success', 'Vehicle updated successfully!');
    }
}
