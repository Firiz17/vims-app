<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Parking Zones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-blue-600">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Create New Parking Zone</h3>

                    <form action="{{ route('locations.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
                        @csrf

                        <div class="flex-1 min-w-[250px]">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Zone Name (e.g., "Zone B")</label>
                            <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="w-56 bg-blue-50 p-2 rounded-md border border-blue-100">
                            <label class="block text-sm font-bold text-blue-900 mb-1">Zone Capacity (Max 20)</label>
                            <input type="number" name="capacity" min="1" max="20" required class="w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-sm h-10">
                                Create Zone
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">📍 Current Yard Utilization</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($locations as $location)
                        @php
                            $vehiclesCount = $location->vehicles_count ?? 0;
                            // Make sure we don't divide by zero!
                            $percentFull = ($location->allowed_capacity > 0) ? ($vehiclesCount / $location->allowed_capacity) * 100 : 0;
                            $barColor = $percentFull >= 100 ? 'bg-red-500' : ($percentFull >= 80 ? 'bg-orange-400' : 'bg-green-500');
                        @endphp

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">{{ $location->name }}</h3>

                            <div class="flex space-x-2">
                                <a href="{{ route('locations.edit', $location->id) }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                    Edit
                                </a>

                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $location->name }}? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-bold bg-red-50 px-3 py-1 rounded-full border border-red-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                            <div class="p-6">
                                <div class="flex justify-between items-end mb-2">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Cars Parked</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ $vehiclesCount }} <span class="text-sm text-gray-400 font-normal">/ {{ $location->allowed_capacity }} allowed</span></p>
                                    </div>

                                    @if($percentFull >= 100)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">FULL</span>
                                    @elseif($percentFull >= 80)
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-bold rounded-full">Almost Full</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Available</span>
                                    @endif
                                </div>

                                <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                                    <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ min($percentFull, 100) }}%"></div>
                                </div>
                                
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 bg-white p-8 text-center rounded-lg shadow-sm border border-gray-200">
                            <p class="text-gray-500">No parking zones created yet. Use the form above to add one.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
