<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check-In New Vehicle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">

                <form action="{{ route('yardstaff.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle VIN</label>
                            <input type="text" name="vin" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Principal (Brand)</label>
                            <input type="text" name="principal" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model</label>
                            <input type="text" name="model" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Arrival Date</label>
                            <input type="date" name="arrival_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Assign Parking Location</label>
                        <select name="location_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" disabled selected>-- Select a Zone --</option>
                            @foreach($locations as $location)
                                @php $spotsLeft = $location->allowed_capacity - $location->vehicles_count; @endphp
                                <option value="{{ $location->id }}" {{ $spotsLeft <= 0 ? 'disabled' : '' }}>
                                    {{ $location->name }} @if($spotsLeft <= 0) (FULL) @else (Available: {{ $spotsLeft }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('yardstaff.inventory') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            Check-In Vehicle
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
