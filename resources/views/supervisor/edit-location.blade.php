<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Parking Zone: ') }} {{ $location->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 border-t-4 border-blue-600">

                <form action="{{ route('locations.update', $location->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Parking Zone Name</label>
                        <input type="text" name="name" value="{{ $location->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="bg-blue-50 p-4 rounded-md border border-blue-100">
                        <label class="block text-sm font-bold text-blue-900 mb-1">Zone Capacity (Max 20)</label>
                        <p class="text-xs text-blue-700 mb-3">Note: You cannot lower the capacity below the number of vehicles currently parked here.</p>

                        <input type="number" name="capacity" value="{{ $location->allowed_capacity }}" min="1" max="20" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        @error('capacity')
                            <p class="text-red-500 text-sm font-bold mt-2">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <a href="{{ route('locations.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel and Go Back
                        </a>
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
