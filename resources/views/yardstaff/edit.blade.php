<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Vehicle: {{ $vehicle->vin }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">

                <form action="{{ route('yardstaff.update', $vehicle->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="bg-gray-50 p-4 rounded-md mb-6">
                        <p><strong>Model:</strong> {{ $vehicle->principal }} {{ $vehicle->model }}</p>
                        <p><strong>Current Status:</strong> {{ $vehicle->status }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Update Status</label>
                        <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Checked-In" {{ $vehicle->status == 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
                            <option value="Pending PDI" {{ $vehicle->status == 'Pending PDI' ? 'selected' : '' }}>Pending PDI (Send to Technician)</option>
                            <option value="Maintenance" {{ $vehicle->status == 'Maintenance' ? 'selected' : '' }}>Maintenance (Major Repairs)</option>
                            <option value="Ready for Delivery" {{ $vehicle->status == 'Ready for Delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                            <option value="Delivered" {{ $vehicle->status == 'Delivered' ? 'selected' : '' }}>Delivered (Leaves Yard)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Move Parking Location</label>
                        <select name="location_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($locations as $location)
                                @php
                                    $spotsLeft = $location->allowed_capacity - $location->vehicles_count;
                                    $isCurrentLocation = $vehicle->location_id == $location->id;
                                @endphp
                                <option value="{{ $location->id }}" {{ ($spotsLeft <= 0 && !$isCurrentLocation) ? 'disabled' : '' }} {{ $isCurrentLocation ? 'selected' : '' }}>
                                    {{ $location->name }}
                                    @if($isCurrentLocation) (Current Location) @elseif($spotsLeft <= 0) (FULL) @else (Available: {{ $spotsLeft }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('yardstaff.inventory') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Save Updates
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
