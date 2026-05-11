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
        // 1. AT-A-GLANCE STATS
        $totalVehicles = \App\Models\Vehicle::where('status', '!=', 'Delivered')->count();
        $readyCount = \App\Models\Vehicle::where('status', 'Ready for Delivery')->count();
        $pendingPdiCount = \App\Models\Vehicle::where('status', 'Pending PDI')->count();
        $damagedCount = \App\Models\Vehicle::where('status', 'Damaged')->count();

        // 2. RECENT DAMAGE REPORTS (Get the latest 5, including who reported it and which car)
        $damageReports = \App\Models\DamageReport::with(['vehicle', 'user'])
                            ->latest('reported_date')
                            ->take(5)
                            ->get();

        // 3. PARKING CAPACITY (To see which zones are getting full)
        $locations = \App\Models\Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        return view('supervisor.dashboard', compact(
            'totalVehicles',
            'readyCount',
            'pendingPdiCount',
            'damagedCount',
            'damageReports',
            'locations'
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
