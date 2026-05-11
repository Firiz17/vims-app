<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\DamageReport;

// use App\Models\User; // We would use this if filtering by staff, but let's keep it simple for now!

class SupervisorController extends Controller
{
    public function index(Request $request)
    {
        // 1. AT-A-GLANCE STATS (Leave these exactly as they are)
        $totalVehicles = \App\Models\Vehicle::where('status', '!=', 'Delivered')->count();
        $readyCount = \App\Models\Vehicle::where('status', 'Ready for Delivery')->count();
        $pendingPdiCount = \App\Models\Vehicle::where('status', 'Pending PDI')->count();
        $damagedCount = \App\Models\Vehicle::where('status', 'Damaged')->count();

        // 2. THE DYNAMIC DAMAGE FEED
        // Start the query builder (but don't get the data yet!)
        $query = \App\Models\DamageReport::with(['vehicle', 'user'])->latest('reported_date');

        // Apply Severity Filter if selected
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Apply Role Filter if selected (Looks at the User table!)
        if ($request->filled('role')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Now execute the query to get all the filtered results
        $damageReports = $query->get();

        // 3. PARKING CAPACITY (Leave this exactly as it is)
        $locations = \App\Models\Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        $agingVehicles = \App\Models\Vehicle::with('location')
            ->where('status', '!=', 'Delivered')
            ->orderBy('created_at', 'asc') // Ascending means oldest dates (longest days) are first
            ->take(10) // Only show the top 10 oldest offenders to keep the UI clean
            ->get();

        return view('supervisor.dashboard', compact(
            'totalVehicles',
            'readyCount',
            'pendingPdiCount',
            'damagedCount',
            'damageReports',
            'locations',
            'agingVehicles'
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
