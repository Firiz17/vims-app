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
        $locations = Location::all();
        // Notice the dot syntax: folderName.fileName
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

        // Create the vehicle!
        Vehicle::create([
            'vin' => $request->vin,
            'model' => $request->model,
            'principal' => $request->principal,
            'status' => 'Checked-In', // Default status
            'arrival_date' => $request->arrival_date,
            'location_id' => $request->location_id,
        ]);

        // Send them back to the form with a success message
        return back()->with('success', 'Vehicle checked in successfully!');
    }
}
