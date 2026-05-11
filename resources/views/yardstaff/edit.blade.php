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
                        <select name="status" id="statusDropdown" onchange="toggleDamageForm()" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Checked-In" {{ $vehicle->status == 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
                            <option value="Pending PDI" {{ $vehicle->status == 'Pending PDI' ? 'selected' : '' }}>Pending PDI</option>
                            <option value="Damaged" {{ $vehicle->status == 'Damaged' || $vehicle->status == 'Maintenance' ? 'selected' : '' }}>Damaged</option>
                            <option value="Ready for Delivery" {{ $vehicle->status == 'Ready for Delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                            <option value="Delivered" {{ $vehicle->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>

                    <div id="damageForm" style="display: {{ ($vehicle->status == 'Damaged' || $vehicle->status == 'Maintenance') ? 'block' : 'none' }};" class="p-4 bg-red-50 border border-red-200 rounded-md">
                        <h3 class="text-red-800 font-bold mb-4">⚠ File Damage Report</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-red-700">Severity Level</label>
                            <select name="damage_severity" class="mt-1 block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="Low">Low (Scratches, Dents)</option>
                                <option value="Medium">Medium (Broken Glass, Parts Missing)</option>
                                <option value="High">High (Engine/Structural Damage)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-red-700">Damage Description</label>
                            <textarea name="damage_description" rows="3" placeholder="Describe the damage..." class="mt-1 block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Move Parking Location</label>
                        <select name="location_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($locations as $location)
                                @php
                                    $parked = $location->vehicles_count ?? 0;
                                    $remaining = $location->allowed_capacity - $parked;

                                    // Identify if this is where the car is ALREADY parked
                                    $isCurrentLocation = ($vehicle->location_id == $location->id);
                                @endphp

                                @if($remaining <= 0 && !$isCurrentLocation)
                                    <option value="{{ $location->id }}" disabled class="text-red-500 font-bold">
                                        ❌ {{ $location->name }} (FULL)
                                    </option>
                                @else
                                    <option value="{{ $location->id }}" {{ $isCurrentLocation ? 'selected' : '' }} class="text-gray-900">
                                        📍 {{ $location->name }} ({{ $remaining }} spots remaining)
                                    </option>
                                @endif
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

                <script>
                    function toggleDamageForm() {
                        var status = document.getElementById('statusDropdown').value;
                        var damageForm = document.getElementById('damageForm');

                        // If they select "Damaged", show the red box. Otherwise, hide it!
                        if (status === 'Damaged') {
                            damageForm.style.display = 'block';
                        } else {
                            damageForm.style.display = 'none';
                        }
                    }
                </script>

            </div>
        </div>
    </div>
</x-app-layout>
