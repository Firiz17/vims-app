<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pre-Delivery Inspection (PDI)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">

                <div class="bg-purple-50 border-l-4 border-purple-600 p-4 mb-8 rounded-r-md">
                    <div class="flex justify-between">
                        <div>
                            @php
                                // SMART NAME FIX: Removes the brand name from the model if they typed it twice, and capitalizes it!
                                $cleanModel = trim(str_ireplace($vehicle->principal, '', $vehicle->model));
                                $displayName = ucwords($vehicle->principal . ' ' . $cleanModel);
                            @endphp
                            <h3 class="text-lg font-bold text-purple-900">{{ $displayName }}</h3>
                            <p class="text-sm text-purple-700 font-mono mt-1">VIN: #{{ $vehicle->vin }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-purple-700">Location</p>
                            <p class="font-bold text-purple-900">{{ $vehicle->location ? $vehicle->location->name : 'Unknown' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('technician.pdi.store', $vehicle->id) }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="bg-red-50 p-6 rounded-md border border-red-100">
                        <h4 class="text-md font-bold text-red-900 border-b border-red-200 pb-2 mb-4">🛑 Mandatory Checks (Required for Delivery)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="inline-flex items-center p-3 bg-white border rounded-md hover:bg-gray-50 cursor-pointer shadow-sm">
                                <input type="checkbox" name="oil_level" value="1" class="rounded text-purple-600 focus:ring-purple-500 h-5 w-5">
                                <span class="ml-3 text-gray-700 font-medium">Engine Oil Level</span>
                            </label>

                            <label class="inline-flex items-center p-3 bg-white border rounded-md hover:bg-gray-50 cursor-pointer shadow-sm">
                                <input type="checkbox" name="coolant" value="1" class="rounded text-purple-600 focus:ring-purple-500 h-5 w-5">
                                <span class="ml-3 text-gray-700 font-medium">Coolant Levels</span>
                            </label>

                            <label class="inline-flex items-center p-3 bg-white border rounded-md hover:bg-gray-50 cursor-pointer shadow-sm">
                                <input type="checkbox" name="interior" value="1" class="rounded text-purple-600 focus:ring-purple-500 h-5 w-5">
                                <span class="ml-3 text-gray-700 font-medium">Interior Cleanliness</span>
                            </label>

                            <label class="inline-flex items-center p-3 bg-white border rounded-md hover:bg-gray-50 cursor-pointer shadow-sm">
                                <input type="checkbox" name="tyre" value="1" class="rounded text-purple-600 focus:ring-purple-500 h-5 w-5">
                                <span class="ml-3 text-gray-700 font-medium">Tyre Condition & Pressure</span>
                            </label>
                        </div>
                        <p class="text-xs text-red-600 mt-3">* If any of these are left unchecked, the vehicle will remain in 'Pending PDI' status.</p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-md border border-gray-200">
                        <h4 class="text-md font-bold text-gray-800 border-b border-gray-300 pb-2 mb-4">ℹ️ Optional Accessories</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="inline-flex items-center p-3 bg-white border rounded-md hover:bg-gray-50 cursor-pointer shadow-sm">
                                <input type="checkbox" name="tinted_windows" value="1" class="rounded text-gray-500 focus:ring-purple-500 h-5 w-5">
                                <span class="ml-3 text-gray-700">Tinted Windows Installed</span>
                            </label>

                            <label class="inline-flex items-center p-3 bg-white border rounded-md hover:bg-gray-50 cursor-pointer shadow-sm">
                                <input type="checkbox" name="dashcam" value="1" class="rounded text-gray-500 focus:ring-purple-500 h-5 w-5">
                                <span class="ml-3 text-gray-700">Dashcam Installed</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Technician Notes</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="List any missing accessories, damages, or reasons for failing the PDI..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('technician.dashboard') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                            Submit Inspection
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
