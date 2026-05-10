<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Yard Staff Inventory') }}
            </h2>
            <a href="{{ route('yardstaff.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                + Check-In New Vehicle
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-4 mb-6 shadow-sm sm:rounded-lg border border-gray-200">
                <form action="{{ route('yardstaff.inventory') }}" method="GET" class="flex flex-wrap gap-4 items-end">

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search VIN / Model</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Type to search..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="Checked-In" {{ request('status') == 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
                            <option value="Pending PDI" {{ request('status') == 'Pending PDI' ? 'selected' : '' }}>Pending PDI</option>
                            <option value="Damaged" {{ request('status') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="Ready for Delivery" {{ request('status') == 'Ready for Delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                            <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>

                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Location</label>
                        <select name="location_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Locations</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-sm">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'location_id']))
                            <a href="{{ route('yardstaff.inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow-sm text-center">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
    @forelse($vehicles as $vehicle)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-mono font-bold text-gray-700 tracking-wider">
                    #{{ $vehicle->vin }}
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900">
                    {{ $vehicle->principal }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $vehicle->model }}
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
                @if($vehicle->status == 'Damaged')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        {{ $vehicle->status }}
                    </span>
                @elseif($vehicle->status == 'Ready for Delivery')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $vehicle->status }}
                    </span>
                @elseif($vehicle->status == 'Pending PDI')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        {{ $vehicle->status }}
                    </span>
                @elseif($vehicle->status == 'Delivered')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ $vehicle->status }}
                    </span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $vehicle->status }}
                    </span>
                @endif
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $vehicle->location ? $vehicle->location->name : 'Unassigned' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('yardstaff.edit', $vehicle->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Update / Move</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                No vehicles found matching your filters.
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
