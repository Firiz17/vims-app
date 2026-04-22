<!DOCTYPE html>
<html>
<head>
    <title>VIMS - Current Inventory</title>
</head>
<body>
    <h1>Current Inventory</h1>

    <a href="{{ url('/yardstaff/check-in') }}"><button>+ Check-In New Vehicle</button></a>
    <br><br>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>VIN</th>
                <th>Vehicle Details</th>
                <th>Arrival Date</th>
                <th>Status</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->vin }}</td>
                    <td>{{ $vehicle->principal }} {{ $vehicle->model }}</td>
                    <td>{{ $vehicle->arrival_date }}</td>
                    <td><strong>{{ $vehicle->status }}</strong></td>
                    <td>{{ $vehicle->location ? $vehicle->location->name : 'Unassigned' }}</td>
                    <td>
                        <a href="{{ route('vehicle.edit', $vehicle->id) }}"><button>Edit / Update</button></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No vehicles in inventory yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
