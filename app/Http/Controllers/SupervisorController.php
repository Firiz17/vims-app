<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\DamageReport;
// use App\Models\User; // We would use this if filtering by staff, but let's keep it simple for now!

class SupervisorController extends Controller
{
    public function index()
    {
        // 1. Calculate the big numbers for the top of the dashboard
        // We exclude 'Delivered' because they are no longer in the yard
        $totalInYard = Vehicle::where('status', '!=', 'Delivered')->count();

        $pendingPdiCount = Vehicle::where('status', 'Pending PDI')->count();
        $maintenanceCount = Vehicle::where('status', 'Damaged')->count();
        $readyCount = Vehicle::where('status', 'Ready for Delivery')->count();

        // 2. Fetch the 5 most recent damage reports so the Supervisor sees them instantly
        // Using 'with' pulls in the connected vehicle data so we don't get N+1 query errors
        $recentDamages = DamageReport::with('vehicle')->latest('reported_date')->take(5)->get();

        // 3. Fetch the master list of ALL vehicles
        $allVehicles = Vehicle::with('location')->latest()->get();

        // Pass all this data to the view
        return view('supervisor.dashboard', compact(
            'totalInYard',
            'pendingPdiCount',
            'maintenanceCount',
            'readyCount',
            'recentDamages',
            'allVehicles'
        ));
    }
    public function manageLocations()
    {
        // Fetch all locations, and automatically count how many vehicles are parked there
        // (excluding delivered vehicles since they left the yard)
        $locations = \App\Models\Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        return view('supervisor.locations', compact('locations'));
    }

    // Save a new Location
    public function storeLocation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:locations,name',
            'total_capacity' => 'required|integer|min:1',
            'allowed_capacity' => 'required|integer|min:1|lte:total_capacity',
        ]);

        \App\Models\Location::create([
            'name' => $request->name,
            'total_capacity' => $request->total_capacity,
            'allowed_capacity' => $request->allowed_capacity,
        ]);

        return redirect()->route('supervisor.locations')->with('success', 'New parking location created successfully!');
    }
    // Show the Edit Form for a specific location
    public function editLocation(\App\Models\Location $location)
    {
        return view('supervisor.edit-location', compact('location'));
    }

    // Save the edited changes
    public function updateLocation(Request $request, \App\Models\Location $location)
    {
        $request->validate([
            // Must be unique, but ignore the current location's ID
            'name' => 'required|string|unique:locations,name,' . $location->id,
            'total_capacity' => 'required|integer|min:1',
            'allowed_capacity' => 'required|integer|min:1|lte:total_capacity', // Smart Validation stays!
        ]);

        $location->update([
            'name' => $request->name,
            'total_capacity' => $request->total_capacity,
            'allowed_capacity' => $request->allowed_capacity,
        ]);

        return redirect()->route('supervisor.locations')->with('success', 'Parking zone updated successfully!');
    }
}
