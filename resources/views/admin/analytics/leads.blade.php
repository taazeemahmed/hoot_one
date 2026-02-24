@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('admin.analytics.index') }}" class="text-gray-500 hover:text-gray-700">Analytics</a></li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-gray-700 font-medium">Lead Analytics</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">Lead Analytics</h1>
                <p class="mt-1 text-sm text-gray-500">Deep dive into lead generation and conversion metrics</p>
            </div>
            <div>
                <form method="GET" class="flex items-center gap-3">
                    <select name="period" onchange="this.form.submit()" class="rounded-xl border-gray-200 text-sm focus:ring-slate-500 focus:border-slate-500">
                        <option value="30" {{ request('period', 30) == 30 ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="60" {{ request('period') == 60 ? 'selected' : '' }}>Last 60 Days</option>
                        <option value="90" {{ request('period') == 90 ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="365" {{ request('period') == 365 ? 'selected' : '' }}>Last Year</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">New Leads</div>
                <div class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($stats['new'] ?? 0) }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Assigned</div>
                <div class="mt-1 text-2xl font-bold text-indigo-600">{{ number_format($stats['assigned'] ?? 0) }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">In Progress</div>
                <div class="mt-1 text-2xl font-bold text-amber-600">{{ number_format(($stats['contacted'] ?? 0) + ($stats['negotiating'] ?? 0)) }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Converted</div>
                <div class="mt-1 text-2xl font-bold text-orange-600">{{ number_format($stats['converted'] ?? 0) }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Lost</div>
                <div class="mt-1 text-2xl font-bold text-red-600">{{ number_format($stats['lost'] ?? 0) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Lead Funnel Visualization -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Lead Funnel</h3>
                    <p class="text-sm text-gray-500">Conversion journey visualization</p>
                </div>
                <div class="p-6">
                    @php
                        $total = array_sum($stats);
                        $funnelStages = [
                            ['name' => 'New', 'count' => $stats['new'] ?? 0, 'color' => 'blue'],
                            ['name' => 'Assigned', 'count' => $stats['assigned'] ?? 0, 'color' => 'indigo'],
                            ['name' => 'Contacted', 'count' => $stats['contacted'] ?? 0, 'color' => 'purple'],
                            ['name' => 'Negotiating', 'count' => $stats['negotiating'] ?? 0, 'color' => 'amber'],
                            ['name' => 'Converted', 'count' => $stats['converted'] ?? 0, 'color' => 'green'],
                        ];
                    @endphp
                    <div class="space-y-3">
                        @foreach($funnelStages as $index => $stage)
                        @php
                            $percentage = $total > 0 ? ($stage['count'] / $total) * 100 : 0;
                            $width = max(20, 100 - ($index * 15));
                        @endphp
                        <div class="flex items-center justify-center">
                            <div class="relative bg-{{ $stage['color'] }}-100 rounded-lg h-12 flex items-center justify-between px-4 transition-all"
                                 style="width: {{ $width }}%">
                                <span class="font-medium text-{{ $stage['color'] }}-800">{{ $stage['name'] }}</span>
                                <span class="font-bold text-{{ $stage['color'] }}-900">{{ number_format($stage['count']) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Source Performance -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Source Performance</h3>
                    <p class="text-sm text-gray-500">Lead quality by acquisition channel</p>
                </div>
                <div class="p-6">
                    <canvas id="sourcePerformanceChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Conversion by Source Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl mb-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Detailed Source Analysis</h3>
                <p class="text-sm text-gray-500">Conversion rates and performance by lead source</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Leads</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Converted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($conversionBySource as $source)
                        @php
                            $rate = $source->total > 0 ? ($source->converted / $source->total) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $sourceIcons = [
                                            'facebook' => '📱',
                                            'google' => '🔍',
                                            'website' => '🌐',
                                            'referral' => '👥',
                                            'whatsapp' => '💬',
                                            'company_direct' => '🏢',
                                        ];
                                        $icon = $sourceIcons[strtolower($source->lead_source ?? '')] ?? '📋';
                                    @endphp
                                    <span class="text-xl mr-2">{{ $icon }}</span>
                                    <span class="text-sm font-medium text-gray-900 capitalize">{{ $source->lead_source ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ number_format($source->total) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ number_format($source->converted) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ number_format($source->lost) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold {{ $rate >= 20 ? 'text-orange-600' : ($rate >= 10 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ number_format($rate, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $rate >= 20 ? 'bg-orange-500' : ($rate >= 10 ? 'bg-amber-500' : 'bg-red-500') }}"
                                         style="width: {{ min($rate, 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daily Lead Trends -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Daily Lead Generation</h3>
                <p class="text-sm text-gray-500">Lead volume over time</p>
            </div>
            <div class="p-6">
                <canvas id="dailyTrendsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Source Performance Chart
    const sourceCtx = document.getElementById('sourcePerformanceChart').getContext('2d');
    const sourceData = @json($conversionBySource);

    new Chart(sourceCtx, {
        type: 'bar',
        data: {
            labels: sourceData.map(d => d.lead_source || 'Unknown'),
            datasets: [{
                label: 'Converted',
                data: sourceData.map(d => d.converted),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
            }, {
                label: 'Lost',
                data: sourceData.map(d => d.lost),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
            }, {
                label: 'Pending',
                data: sourceData.map(d => d.total - d.converted - d.lost),
                backgroundColor: 'rgba(156, 163, 175, 0.5)',
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
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });

    // Daily Trends Chart
    const dailyCtx = document.getElementById('dailyTrendsChart').getContext('2d');
    const dailyData = @json($dailyTrends ?? []);

    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'New Leads',
                data: dailyData.map(d => d.count),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection
