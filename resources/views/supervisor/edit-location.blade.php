<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit parking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                </div>
        </div>
    </div>
    <h1>Edit Parking Zone: {{ $location->name }}</h1>
    <a href="{{ route('supervisor.locations') }}">← Cancel and Go Back</a>
    <br><br>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div style="border: 2px solid black; padding: 20px; width: 50%;">
        <form action="{{ route('supervisor.locations.update', $location->id) }}" method="POST">
            @csrf
            @method('PUT') <label>Zone Name:</label><br>
            <input type="text" name="name" value="{{ $location->name }}" required><br><br>

            <label>Total Physical Capacity:</label><br>
            <input type="number" name="total_capacity" value="{{ $location->total_capacity }}" min="1" required><br><br>

            <label>Allowed Parking Limit (Operational):</label><br>
            <input type="number" name="allowed_capacity" value="{{ $location->allowed_capacity }}" min="1" required><br><br>

            <button type="submit" style="padding: 10px 20px; background-color: blue; color: white;">Save Changes</button>
        </form>
    </div>
</x-app-layout>
