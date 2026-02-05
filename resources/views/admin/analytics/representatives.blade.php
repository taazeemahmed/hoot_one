@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-3" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.analytics.index') }}" class="text-gray-500 hover:text-gray-700">Analytics</a></li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-gray-700 font-medium">Representative Performance</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Representative Performance</h1>
            <p class="mt-1 text-sm text-gray-500">Performance and conversion metrics by representative</p>
        </div>

        <!-- Performance Leaderboard -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl mb-8">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Performance Leaderboard</h3>
                        <p class="text-sm text-gray-500">Ranked by conversion rate and lead volume</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Representative</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Leads</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Converted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conv. Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($representatives as $index => $rep)
                        @php
                            $rate = $rep->total_leads > 0 ? ($rep->converted / $rep->total_leads) * 100 : 0;
                            $pending = $rep->total_leads - $rep->converted - $rep->lost;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($index < 3)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-gray-100 text-gray-800' : 'bg-amber-100 text-amber-800') }} font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                @else
                                <span class="text-gray-500 font-medium ml-2">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($rep->name) }}&color=1e293b&background=f1f5f9" 
                                             alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $rep->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $rep->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $rep->country ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ number_format($rep->total_leads) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ number_format($rep->converted) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ number_format($rep->lost) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ number_format($pending) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-bold {{ $rate >= 25 ? 'text-green-600' : ($rate >= 15 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ number_format($rate, 1) }}%
                                    </span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $rate >= 25 ? 'bg-green-500' : ($rate >= 15 ? 'bg-amber-500' : 'bg-red-500') }}" 
                                             style="width: {{ min($rate, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No representatives found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Performance Comparison Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Conversion Comparison</h3>
                    <p class="text-sm text-gray-500">Side-by-side performance view</p>
                </div>
                <div class="p-6">
                    <canvas id="repComparisonChart" height="300"></canvas>
                </div>
            </div>

            <!-- Country Distribution -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Converted Share</h3>
                    <p class="text-sm text-gray-500">Contribution to total conversions</p>
                </div>
                <div class="p-6">
                    <canvas id="conversionShareChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Response Time & Activity -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Activity Summary</h3>
                <p class="text-sm text-gray-500">Lead handling and response metrics</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($representatives->take(6) as $rep)
                    <div class="border rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-3">
                            <img class="h-10 w-10 rounded-full" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode($rep->name) }}&color=1e293b&background=f1f5f9" 
                                 alt="">
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-900">{{ $rep->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $rep->country ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Patients</span>
                                <span class="font-medium text-gray-900">{{ number_format($rep->converted) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Active Leads</span>
                                <span class="font-medium text-amber-600">{{ number_format($rep->total_leads - $rep->converted - $rep->lost) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Lost Leads</span>
                                <span class="font-medium text-red-600">{{ number_format($rep->lost) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const reps = @json($representatives);
    
    // Comparison Chart
    const compCtx = document.getElementById('repComparisonChart').getContext('2d');
    new Chart(compCtx, {
        type: 'bar',
        data: {
            labels: reps.slice(0, 8).map(r => r.name.split(' ')[0]),
            datasets: [{
                label: 'Converted',
                data: reps.slice(0, 8).map(r => r.converted),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
            }, {
                label: 'Lost',
                data: reps.slice(0, 8).map(r => r.lost),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
            }, {
                label: 'Pending',
                data: reps.slice(0, 8).map(r => r.total_leads - r.converted - r.lost),
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });

    // Converted Share Chart
    const shareCtx = document.getElementById('conversionShareChart').getContext('2d');
    new Chart(shareCtx, {
        type: 'doughnut',
        data: {
            labels: reps.slice(0, 6).map(r => r.name),
            datasets: [{
                data: reps.slice(0, 6).map(r => r.converted || 0),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(168, 85, 247)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(107, 114, 128)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
@endpush
@endsection
