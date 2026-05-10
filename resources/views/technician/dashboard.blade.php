<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Technician Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                </div>
        </div>
    </div>
    <h1>Technician Dashboard</h1>
    <h2>Vehicles Pending PDI</h2>

    @if(session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>VIN</th>
                <th>Model</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->vin }}</td>
                    <td>{{ $vehicle->principal }} {{ $vehicle->model }}</td>
                    <td>{{ $vehicle->location ? $vehicle->location->name : 'Unassigned' }}</td>
                    <td>
                        <a href="{{ route('technician.pdi.create', $vehicle->id) }}"><button>Perform PDI</button></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No vehicles currently need inspection! Take a break.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
