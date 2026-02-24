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
                        <span class="text-gray-700 font-medium">Marketing Team</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Marketing Team Performance</h1>
            <p class="mt-1 text-sm text-gray-500">Lead generation and conversion metrics by marketing member</p>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            @php
                $totalLeads = $marketingMembers->sum('total_leads');
                $totalConverted = $marketingMembers->sum('converted');
                $totalLost = $marketingMembers->sum('lost');
                $overallRate = $totalLeads > 0 ? ($totalConverted / $totalLeads) * 100 : 0;
            @endphp
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Total Leads Generated</div>
                <div class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($totalLeads) }}</div>
                <div class="mt-1 text-xs text-gray-400">by {{ $marketingMembers->count() }} team members</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Successfully Converted</div>
                <div class="mt-1 text-2xl font-bold text-orange-600">{{ number_format($totalConverted) }}</div>
                <div class="mt-1 text-xs text-gray-400">{{ number_format($overallRate, 1) }}% conversion rate</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Lost Leads</div>
                <div class="mt-1 text-2xl font-bold text-red-600">{{ number_format($totalLost) }}</div>
                <div class="mt-1 text-xs text-gray-400">{{ $totalLeads > 0 ? number_format(($totalLost / $totalLeads) * 100, 1) : 0 }}% loss rate</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5">
                <div class="text-sm font-medium text-gray-500">Pending Leads</div>
                <div class="mt-1 text-2xl font-bold text-amber-600">{{ number_format($totalLeads - $totalConverted - $totalLost) }}</div>
                <div class="mt-1 text-xs text-gray-400">in pipeline</div>
            </div>
        </div>

        <!-- Marketing Members Performance Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl mb-8">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Member Performance</h3>
                        <p class="text-sm text-gray-500">Individual contribution and conversion metrics</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leads Added</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Converted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conv. Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contribution</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($marketingMembers as $index => $member)
                        @php
                            $rate = $member->total_leads > 0 ? ($member->converted / $member->total_leads) * 100 : 0;
                            $contribution = $totalLeads > 0 ? ($member->total_leads / $totalLeads) * 100 : 0;
                            $assigned = $member->total_leads - $member->unassigned;
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
                                             src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=1e293b&background=f1f5f9"
                                             alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($member->total_leads) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($assigned) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ number_format($member->converted) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ number_format($member->lost) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-bold {{ $rate >= 20 ? 'text-orange-600' : ($rate >= 10 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ number_format($rate, 1) }}%
                                    </span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $rate >= 20 ? 'bg-orange-500' : ($rate >= 10 ? 'bg-amber-500' : 'bg-red-500') }}"
                                             style="width: {{ min($rate, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-600">{{ number_format($contribution, 1) }}%</span>
                                    <div class="ml-2 w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min($contribution, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No marketing members found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Lead Generation Comparison -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Lead Generation</h3>
                    <p class="text-sm text-gray-500">Leads added by each member</p>
                </div>
                <div class="p-6">
                    <canvas id="leadGenChart" height="300"></canvas>
                </div>
            </div>

            <!-- Conversion Performance -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Conversion Performance</h3>
                    <p class="text-sm text-gray-500">Success rate comparison</p>
                </div>
                <div class="p-6">
                    <canvas id="conversionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Member Cards -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Team at a Glance</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($marketingMembers->take(6) as $member)
                @php
                    $rate = $member->total_leads > 0 ? ($member->converted / $member->total_leads) * 100 : 0;
                    $pending = $member->total_leads - $member->converted - $member->lost;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <img class="h-12 w-12 rounded-full"
                             src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=1e293b&background=f1f5f9"
                             alt="">
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">{{ $member->name }}</h4>
                            <p class="text-sm text-gray-500">Marketing Team</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ $member->total_leads }}</div>
                            <div class="text-xs text-gray-500">Total</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-orange-600">{{ $member->converted }}</div>
                            <div class="text-xs text-gray-500">Converted</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-amber-600">{{ $pending }}</div>
                            <div class="text-xs text-gray-500">Pending</div>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Conversion Rate</span>
                            <span class="text-sm font-bold {{ $rate >= 20 ? 'text-orange-600' : ($rate >= 10 ? 'text-amber-600' : 'text-red-600') }}">
                                {{ number_format($rate, 1) }}%
                            </span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $rate >= 20 ? 'bg-orange-500' : ($rate >= 10 ? 'bg-amber-500' : 'bg-red-500') }}"
                                 style="width: {{ min($rate * 2, 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const members = @json($marketingMembers);

    // Lead Generation Chart
    const leadGenCtx = document.getElementById('leadGenChart').getContext('2d');
    new Chart(leadGenCtx, {
        type: 'bar',
        data: {
            labels: members.map(m => m.name.split(' ')[0]),
            datasets: [{
                label: 'Leads Added',
                data: members.map(m => m.total_leads),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
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

    // Conversion Chart
    const convCtx = document.getElementById('conversionChart').getContext('2d');
    new Chart(convCtx, {
        type: 'bar',
        data: {
            labels: members.map(m => m.name.split(' ')[0]),
            datasets: [{
                label: 'Converted',
                data: members.map(m => m.converted),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
            }, {
                label: 'Lost',
                data: members.map(m => m.lost),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
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
</script>
@endpush
@endsection
