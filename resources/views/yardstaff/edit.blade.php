<!DOCTYPE html>
<html>
<head>
    <title>VIMS - Edit Vehicle</title>
</head>
<body>
    <h1>Update Vehicle: {{ $vehicle->vin }}</h1>
    <h3>{{ $vehicle->principal }} {{ $vehicle->model }}</h3>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST">
        @csrf
        @method('PUT') <h3>1. Update Location (UC03)</h3>
        <label>Vehicle Status:</label><br>
        <select name="status" required>
            <option value="Checked-In" {{ $vehicle->status == 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
            <option value="Pending PDI" {{ $vehicle->status == 'Pending PDI' ? 'selected' : '' }}>Pending PDI</option>
            {{-- <option value="Maintenance" {{ $vehicle->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option> --}}
            <option value="Ready for Delivery" {{ $vehicle->status == 'Ready for Delivery' ? 'selected' : '' }}>Ready for Delivery</option>
            <option value="Delivered" {{ $vehicle->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
        </select>
        <br><br>
        
        <label>Update Location:</label><br>
        <select name="location_id" required>
            @foreach($locations as $location)
                @php
                    $spotsLeft = $location->allowed_capacity - $location->vehicles_count;
                    $isCurrentLocation = $vehicle->location_id == $location->id;
                @endphp

                <option value="{{ $location->id }}"
                    {{ ($spotsLeft <= 0 && !$isCurrentLocation) ? 'disabled' : '' }}
                    {{ $isCurrentLocation ? 'selected' : '' }}>

                    {{ $location->name }}

                    @if($isCurrentLocation)
                        (Current Location)
                    @elseif($spotsLeft <= 0)
                        (FULL)
                    @else
                        (Available: {{ $spotsLeft }})
                    @endif
                </option>
            @endforeach
        </select>
        <br><br>

        <hr>
        <h3>Vehicle Damage History</h3>
        @if($vehicle->damageReports->count() > 0)
            <ul style="color: darkred;">
                @foreach($vehicle->damageReports as $report)
                    <li>
                        <strong>{{ $report->reported_date }} ({{ $report->severity }} Severity):</strong>
                        {{ $report->description }}
                    </li>
                @endforeach
            </ul>
        @else
            <p style="color: green;">No previous damage reported.</p>
        @endif
        <hr>

        <h3>2. Log New Damage (UC04)</h3>
        <p><i>Leave blank if there is no new damage to report.</i></p>

        <label>Damage Description:</label><br>
        <textarea name="damage_description" rows="3" cols="40"></textarea><br><br>

        <label>Severity:</label><br>
        <select name="damage_severity">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
        <br><br>

        <button type="submit">Save Updates</button>
        <a href="{{ route('inventory.index') }}">Cancel</a>
    </form>
</body>
</html>
