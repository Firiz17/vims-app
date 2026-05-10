<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Technician PDI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                </div>
        </div>
    </div>
    <h1>Pre-Delivery Inspection (PDI)</h1>
    <h3>Vehicle: {{ $vehicle->principal }} {{ $vehicle->model }} ({{ $vehicle->vin }})</h3>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('technician.pdi.store', $vehicle->id) }}" method="POST">
        @csrf

        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Inspection Item</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>Tyres</td>
                <td>
                    <select name="tyre" required>
                        <option value="Checked">Checked</option>
                        <option value="Not Checked">Not Checked</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Oil Level</td>
                <td>
                    <select name="oil_level" required>
                        <option value="Checked">Checked</option>
                        <option value="Not Checked">Not Checked</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Coolant</td>
                <td>
                    <select name="coolant" required>
                        <option value="Checked">Checked</option>
                        <option value="Not Checked">Not Checked</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Interior Cleanliness</td>
                <td>
                    <select name="interior_cleanliness" required>
                        <option value="Checked">Checked</option>
                        <option value="Not Checked">Not Checked</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tinted Windows</td>
                <td>
                    <select name="tinted_window" required>
                        <option value="Applied">Applied</option>
                        <option value="Not Applied">Not Applied</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Dashcam</td>
                <td>
                    <select name="dashcam" required>
                        <option value="Applied">Applied</option>
                        <option value="Not Applied">Not Applied</option>
                    </select>
                </td>
            </tr>
        </table>
        <br>


        <button type="submit" style="padding: 10px 20px;">Submit Inspection</button>
        <a href="{{ route('technician.dashboard') }}">Cancel</a>
    </form>
</x-app-layout>
