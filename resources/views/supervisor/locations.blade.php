<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                </div>
        </div>
    </div>
    <h1>Manage Parking Designations</h1>
    <a href="{{ route('supervisor.dashboard') }}">← Back to Dashboard</a>
    <br><br>

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

    <div style="border: 2px solid black; padding: 20px; width: 50%;">
        <h3>Create New Parking Zone</h3>
        <form action="{{ route('supervisor.locations.store') }}" method="POST">
            @csrf
            <label>Zone Name (e.g., "Zone B", "VIP Warehouse"):</label><br>
            <input type="text" name="name" required><br><br>

            <label>Maximum Car Capacity:</label><br>
            <input type="number" name="total_capacity" value="50" min="1" required><br><br>

            <label>Allowed Parking Limit (Operational):</label><br>
            <input type="number" name="allowed_capacity" value="25" min="1" required><br><br>

            <button type="submit">Create Zone</button>
        </form>
    </div>

    <br><hr><br>

    <h3>Current Yard Utilization</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="80%">
        <thead>
            <tr>
                <th>Zone Name</th>
                <th>Cars Parked</th>
                <th>Total Capacity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locations as $location)
                <tr>
                    <td><strong>{{ $location->name }}</strong></td>
                    <td style="text-align: center;">{{ $location->vehicles_count }}</td>
                    <td style="text-align: center;">{{ $location->allowed_capacity }}</td>
                    <td style="text-align: center; color: gray;">(Max: {{ $location->total_capacity }})</td>
                    <td>
                        @if($location->vehicles_count >= $location->allowed_capacity)
                            <strong style="color: red;">FULL (Limit Reached)</strong>
                        @elseif($location->vehicles_count >= ($location->allowed_capacity * 0.8))
                            <strong style="color: orange;">Almost Full</strong>
                        @else
                            <strong style="color: green;">Available</strong>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('supervisor.locations.edit', $location->id) }}">
                            <button style="cursor: pointer;">Edit Zone</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
