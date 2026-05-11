<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Command Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-600">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Vehicles in Yard</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalVehicles }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Ready for Delivery</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $readyCount }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending PDI</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingPdiCount }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Damaged / Grounded</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $damagedCount }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">🚨 Recent Damage & PDI Failures</h3>
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Severity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reported By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($damageReports as $report)
                                    <tr class="hover:bg-red-50 transition">
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                            #{{ $report->vehicle ? $report->vehicle->vin : 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($report->severity == 'High')
                                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">High</span>
                                            @elseif($report->severity == 'Medium')
                                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800">Medium</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Low</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $report->description }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $report->user ? $report->user->name : 'System' }}<br>
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($report->reported_date)->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                            No damage reports! The yard is in perfect condition.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">📍 Zone Capacity</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($locations as $location)
                            @php
                                $percentFull = ($location->allowed_capacity > 0) ? ($location->vehicles_count / $location->allowed_capacity) * 100 : 0;
                                $barColor = $percentFull >= 90 ? 'bg-red-500' : ($percentFull >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-bold text-gray-700">{{ $location->name }}</span>
                                    <span class="text-gray-500">{{ $location->vehicles_count }} / {{ $location->allowed_capacity }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min($percentFull, 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
