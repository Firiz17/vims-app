<!DOCTYPE html>
<html>
<head>
    <title>VIMS - Vehicle Check-In</title>
</head>
<body>
    <h1>Vehicle Check-In</h1>

    @if(session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('vehicle.store') }}" method="POST">
        @csrf <label>VIN:</label><br>
        <input type="text" name="vin" required><br><br>

        <label>Model (e.g., Hilux):</label><br>
        <input type="text" name="model" required><br><br>

        <label>Principal (Brand):</label><br>
        <input type="text" name="principal" required><br><br>

        <label>Arrival Date:</label><br>
        <input type="date" name="arrival_date" required><br><br>

        <label>Park in Location:</label><br>
        <select name="location_id" required>
            <option value="">-- Select a Zone --</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}">{{ $location->name }} (Capacity: {{ $location->total_capacity }})</option>
            @endforeach
        </select><br><br>

        <button type="submit">Check-In Vehicle</button>
    </form>
</body>
</html>
