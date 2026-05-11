<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // 1. SHOW THE GRID OF PARKING ZONES
    public function index()
    {
        // Get all locations and count the cars parked in them (ignoring delivered cars)
        $locations = Location::withCount(['vehicles' => function($query) {
            $query->where('status', '!=', 'Delivered');
        }])->get();

        return view('supervisor.locations', compact('locations'));
    }


    // 3. SAVE THE NEW ZONE TO THE DATABASE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:20', // Just one number now!
        ]);

        Location::create([
            'name' => $request->name,
            'allowed_capacity' => $request->capacity,
            'total_capacity' => $request->capacity, // Secretly fill both so the database is happy
        ]);

        return redirect()->route('locations.index')->with('success', 'Parking zone created successfully!');
    }

    // 4. SHOW THE "EDIT ZONE" FORM
    public function edit(Location $location)
    {
        return view('supervisor.edit-location', compact('location'));
    }

    // 5. SAVE THE UPDATES TO THE DATABASE
    public function update(Request $request, Location $location)
    {
        // 1. Validate the new inputs (Cap at 20)
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        // 2. THE FAILSAFE: Count how many cars are currently parked here
        $currentParkedCount = $location->vehicles()->where('status', '!=', 'Delivered')->count();

        // 3. Prevent them from lowering capacity below the actual parked cars
        if ($request->capacity < $currentParkedCount) {
            return back()->withErrors(['capacity' => "Cannot shrink capacity to {$request->capacity}. There are currently {$currentParkedCount} cars parked here!"]);
        }

        // 4. If it's safe, update both database columns
        $location->update([
            'name' => $request->name,
            'allowed_capacity' => $request->capacity,
            'total_capacity' => $request->capacity,
        ]);

        return redirect()->route('locations.index')->with('success', 'Parking zone updated successfully!');
    }

    // 6. DELETE THE PARKING ZONE
    public function destroy(Location $location)
    {
        // SECURITY: Check if cars are currently parked here!
        if ($location->vehicles()->where('status', '!=', 'Delivered')->count() > 0) {
            return back()->withErrors(['Cannot delete this zone. There are still vehicles parked here! Please move them first.']);
        }

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Parking zone deleted successfully!');
    }
}
