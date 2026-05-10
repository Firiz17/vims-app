<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Master Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <table border="1" cellpadding="15" cellspacing="0" width="100%" style="text-align: center; background-color: #f8f9fa;">
                    <tr>
                        <td>
                            <h3>Total in Yard</h3>
                            <h1 style="color: blue;">{{ $totalInYard }}</h1>
                        </td>
                        <td>
                            <h3>Pending PDI</h3>
                            <h1 style="color: orange;">{{ $pendingPdiCount }}</h1>
                        </td>
                        <td>
                            <h3>In Maintenance</h3>
                            <h1 style="color: red;">{{ $maintenanceCount }}</h1>
                        </td>
                        <td>
                            <h3>Ready for Delivery</h3>
                            <h1 style="color: green;">{{ $readyCount }}</h1>
                        </td>
                    </tr>
                </table>

                <br><hr><br>

                <h2 style="color: darkred;">⚠ Recent Damage Reports</h2>
                <table border="1" cellpadding="8" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Date Reported</th>
                            <th>Vehicle VIN</th>
                            <th>Severity</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDamages as $damage)
                            <tr>
                                <td>{{ $damage->reported_date }}</td>
                                <td>{{ $damage->vehicle->vin ?? 'Unknown' }}</td>
                                <td><strong>{{ $damage->severity }}</strong></td>
                                <td>{{ $damage->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: green;">No recent damage reported. Excellent!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <br><hr><br>

                <h2>Master Yard Roster</h2>
                <table border="1" cellpadding="8" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>VIN</th>
                            <th>Details</th>
                            <th>Current Status</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allVehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->vin }}</td>
                                <td>{{ $vehicle->principal }} {{ $vehicle->model }}</td>
                                <td>
                                    @if($vehicle->status == 'Maintenance')
                                        <strong style="color: red;">{{ $vehicle->status }}</strong>
                                    @elseif($vehicle->status == 'Ready for Delivery')
                                        <strong style="color: green;">{{ $vehicle->status }}</strong>
                                    @else
                                        {{ $vehicle->status }}
                                    @endif
                                </td>
                                <td>{{ $vehicle->location ? $vehicle->location->name : 'Unassigned' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
