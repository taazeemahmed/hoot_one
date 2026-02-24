@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Analytics Overview</h1>
            <p class="mt-1 text-sm text-gray-500">Business intelligence and performance metrics</p>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-xl bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Leads</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($kpis['totalLeads']) }}</div>
                                    @if($kpis['leadsGrowth'] > 0)
                                    <span class="ml-2 text-sm font-medium text-orange-600">+{{ $kpis['leadsGrowth'] }}%</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-xl bg-orange-50">
                            <svg class="h-8 w-8 text-hoot-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Converted Patients</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($kpis['convertedPatients']) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-xl bg-purple-50">
                            <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($kpis['conversionRate'], 1) }}%</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-xl bg-amber-50">
                            <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m4-4h8" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($kpis['totalOrders'] ?? 0) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.analytics.leads') }}" class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">Lead Analytics</h3>
                        <p class="mt-1 text-sm text-gray-500">Funnel, sources, and conversion metrics</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.analytics.representatives') }}" class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">Representative Performance</h3>
                        <p class="mt-1 text-sm text-gray-500">Sales, regions, and performance data</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.analytics.marketing') }}" class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">Marketing Team</h3>
                        <p class="mt-1 text-sm text-gray-500">Lead generation and conversion by member</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Monthly Trends Chart -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl mb-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Monthly Trends</h3>
                <p class="text-sm text-gray-500">Lead generation and conversion trends over the last 6 months</p>
            </div>
            <div class="p-6">
                <canvas id="monthlyTrendsChart" height="100"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Lead Funnel -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Lead Funnel</h3>
                    <p class="text-sm text-gray-500">Current lead distribution by status</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $maxFunnel = max(1, collect($funnel)->max('count') ?? 0);
                            $funnelColors = [
                                'new' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-100'],
                                'assigned' => ['bg' => 'bg-indigo-500', 'light' => 'bg-indigo-100'],
                                'contacted' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-100'],
                                'negotiating' => ['bg' => 'bg-amber-500', 'light' => 'bg-amber-100'],
                                'converted' => ['bg' => 'bg-orange-500', 'light' => 'bg-orange-100'],
                                'lost' => ['bg' => 'bg-red-500', 'light' => 'bg-red-100'],
                            ];
                        @endphp
                        @foreach($funnel as $stage)
                        @php
                            $colors = $funnelColors[$stage['status']] ?? ['bg' => 'bg-gray-500', 'light' => 'bg-gray-100'];
                            $width = ($stage['count'] / $maxFunnel) * 100;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $stage['status']) }}</span>
                                <span class="text-gray-500">{{ number_format($stage['count']) }}</span>
                            </div>
                            <div class="w-full {{ $colors['light'] }} rounded-full h-3">
                                <div class="{{ $colors['bg'] }} h-3 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Conversion by Source -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Conversion by Source</h3>
                    <p class="text-sm text-gray-500">Lead performance by acquisition channel</p>
                </div>
                <div class="p-6">
                    <canvas id="sourceChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Country Performance -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl mt-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Country Performance</h3>
                <p class="text-sm text-gray-500">Lead conversion rates by country</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Leads</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Converted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($conversionByCountry as $country)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $country->country ?? 'Unknown' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($country->total) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ number_format($country->converted) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ number_format($country->lost) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $rate = $country->total > 0 ? ($country->converted / $country->total) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($rate, 1) }}%</span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ min($rate, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Trends Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthlyData = @json($monthlyTrends);

    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Total Leads',
                data: monthlyData.map(d => d.total),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'Converted',
                data: monthlyData.map(d => d.converted),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.3,
                fill: true
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
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Source Chart
    const sourceCtx = document.getElementById('sourceChart').getContext('2d');
    const sourceData = @json($conversionBySource);

    new Chart(sourceCtx, {
        type: 'doughnut',
        data: {
            labels: sourceData.map(d => d.lead_source || 'Unknown'),
            datasets: [{
                data: sourceData.map(d => d.total),
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
