@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/30">
    {{-- ============================================ --}}
    {{-- EXECUTIVE SUMMARY BAR --}}
    {{-- ============================================ --}}
    @if($executiveSummary['overdue_today'] > 0 || $executiveSummary['orders_pending'] > 0 || $executiveSummary['whatsapp_issues'] > 0)
    <div class="bg-slate-900 text-white px-4 py-3 mb-6 rounded-xl shadow-lg">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-6 text-sm">
                @if($executiveSummary['overdue_today'] > 0)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="font-semibold text-red-400">{{ $executiveSummary['overdue_today'] }}</span>
                    <span class="text-gray-400">overdue</span>
                </div>
                @endif

                @if($executiveSummary['renewals_due_today'] > 0)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    <span class="font-semibold text-amber-400">{{ $executiveSummary['renewals_due_today'] }}</span>
                    <span class="text-gray-400">due today</span>
                </div>
                @endif

                @if($executiveSummary['orders_pending'] > 0)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="font-semibold text-blue-400">{{ $executiveSummary['orders_pending'] }}</span>
                    <span class="text-gray-400">pending</span>
                </div>
                @endif

                @if($executiveSummary['unassigned_leads'] > 0)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                    <span class="font-semibold text-purple-400">{{ $executiveSummary['unassigned_leads'] }}</span>
                    <span class="text-gray-400">unassigned</span>
                </div>
                @endif
            </div>

            @if($executiveSummary['insight_text'])
            <div class="text-xs text-gray-400 max-w-md truncate">
                {{ $executiveSummary['insight_text'] }}
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ============================================ --}}
    {{-- SMART KPI CARDS --}}
    {{-- ============================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Patients --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2.5 bg-slate-100 rounded-xl">
                    <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                @if($kpis['patients_growth'] != 0)
                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $kpis['patients_growth'] > 0 ? 'bg-orange-50 text-orange-600' : 'bg-red-50 text-red-600' }}">
                    {{ $kpis['patients_growth'] > 0 ? '+' : '' }}{{ $kpis['patients_growth'] }}%
                </span>
                @endif
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($kpis['total_patients']) }}</div>
            <div class="text-xs text-gray-500">Total Leads · <span class="text-gray-700 font-medium">{{ $kpis['patients_this_month'] }} this month</span></div>
            <a href="{{ route('admin.patients.index') }}" class="mt-3 inline-flex items-center text-xs font-medium text-slate-600 hover:text-slate-900">
                View all <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Converted Patients --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2.5 bg-orange-50 rounded-xl">
                    <svg class="w-5 h-5 text-hoot-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-orange-50 text-orange-600">
                    {{ $kpis['conversion_rate'] }}%
                </span>
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($kpis['converted_patients']) }}</div>
            <div class="text-xs text-gray-500">Converted Patients</div>
            <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-orange-500 h-1.5 rounded-full transition-all" style="width: {{ min($kpis['conversion_rate'], 100) }}%"></div>
            </div>
        </div>

        {{-- Active Orders --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2.5 bg-blue-50 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                @if($kpis['orders_growth'] != 0)
                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $kpis['orders_growth'] > 0 ? 'bg-orange-50 text-orange-600' : 'bg-red-50 text-red-600' }}">
                    {{ $kpis['orders_growth'] > 0 ? '+' : '' }}{{ $kpis['orders_growth'] }}%
                </span>
                @endif
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($kpis['active_orders']) }}</div>
            <div class="text-xs text-gray-500">Active Orders · <span class="text-gray-700 font-medium">{{ $kpis['orders_this_month'] }} this month</span></div>
            <a href="{{ route('admin.orders.index') }}" class="mt-3 inline-flex items-center text-xs font-medium text-slate-600 hover:text-slate-900">
                Manage orders <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Renewals Overview --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow {{ $kpis['overdue_count'] > 0 ? 'ring-2 ring-red-100' : '' }}">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2.5 {{ $kpis['overdue_count'] > 0 ? 'bg-red-50' : 'bg-amber-50' }} rounded-xl">
                    <svg class="w-5 h-5 {{ $kpis['overdue_count'] > 0 ? 'text-red-600' : 'text-amber-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($kpis['overdue_count'] > 0)
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-50 text-red-600 animate-pulse">
                    {{ $kpis['overdue_count'] }} overdue
                </span>
                @endif
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1">{{ $kpis['renewals_due_week'] }}</div>
            <div class="text-xs text-gray-500">Due this week · <span class="text-gray-700 font-medium">{{ $kpis['renewals_due_month'] }} this month</span></div>
            <a href="#renewals-section" class="mt-3 inline-flex items-center text-xs font-medium text-slate-600 hover:text-slate-900">
                View renewals <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MAIN CONTENT: CRITICAL ALERTS + QUICK ACTIONS --}}
    {{-- ============================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

        {{-- NEEDS ATTENTION PANEL --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-50 rounded-lg">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Needs Attention</h3>
                            <p class="text-xs text-gray-500">Ranked by urgency · Take action now</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                        {{ $criticalAlerts->count() }} items
                    </span>
                </div>

                <div class="divide-y divide-gray-50 max-h-[420px] overflow-y-auto">
                    @forelse($criticalAlerts as $alert)
                    <div class="px-5 py-4 hover:bg-gray-50/50 transition-colors group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                {{-- Severity indicator --}}
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($alert['severity'] === 'critical')
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    @else
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-gray-900 truncate">{{ $alert['title'] }}</h4>
                                        <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0
                                            {{ $alert['severity'] === 'critical' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $alert['detail'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ $alert['subtitle'] }}</p>
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="flex items-center gap-2 flex-shrink-0 opacity-60 group-hover:opacity-100 transition-opacity">
                                @if($alert['phone'])
                                <a href="tel:{{ $alert['phone'] }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-green-500 transition-all"
                                   title="Call">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $alert['phone']) }}"
                                   target="_blank"
                                   class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-green-500 transition-all"
                                   title="WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </a>
                                @endif

                                @if($alert['type'] === 'unassigned_lead')
                                <a href="{{ route('admin.patients.show', $alert['patient_id']) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-700 hover:text-white transition-all">
                                    Assign
                                </a>
                                @else
                                <a href="{{ isset($alert['order_id']) ? route('admin.orders.show', $alert['order_id']) : route('admin.patients.show', $alert['patient_id']) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-700 hover:text-white transition-all">
                                    View
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-orange-50 rounded-full text-hoot-maroon">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="font-medium text-gray-900">All caught up!</p>
                        <p class="text-sm text-gray-500">No critical issues right now</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- QUICK ADMIN ACTIONS + SYSTEM HEALTH --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if($unassignedLeads->count() > 0)
                    <a href="{{ route('admin.leads.index', ['filter' => 'unassigned']) }}"
                       class="flex items-center justify-between p-3 rounded-xl bg-purple-50 hover:bg-purple-100 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                                <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-purple-900">Assign {{ $unassignedLeads->count() }} leads</span>
                        </div>
                        <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif

                    @if($kpis['overdue_count'] > 0)
                    <a href="{{ route('admin.orders.index', ['filter' => 'overdue']) }}"
                       class="flex items-center justify-between p-3 rounded-xl bg-red-50 hover:bg-red-100 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-red-900">Review {{ $kpis['overdue_count'] }} overdue</span>
                        </div>
                        <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif

                    <a href="{{ route('admin.orders.create') }}"
                       class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-200 rounded-lg group-hover:bg-gray-300 transition-colors">
                                <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Create new order</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('admin.analytics.index') }}"
                       class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-200 rounded-lg group-hover:bg-gray-300 transition-colors">
                                <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">View analytics</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- WhatsApp & System Health --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900">System Health</h3>
                    <span class="flex items-center gap-1.5 text-xs {{ $whatsappStats['failed_week'] > 5 ? 'text-amber-600' : 'text-orange-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $whatsappStats['failed_week'] > 5 ? 'bg-amber-500' : 'bg-orange-500' }}"></span>
                        {{ $whatsappStats['failed_week'] > 5 ? 'Issues detected' : 'All systems normal' }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-600">WhatsApp sent today</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $whatsappStats['success_today'] }}/{{ $whatsappStats['total_sent_today'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-600">Failed (7 days)</span>
                        <span class="text-sm font-semibold {{ $whatsappStats['failed_week'] > 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $whatsappStats['failed_week'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600">Last message sent</span>
                        <span class="text-sm text-gray-500">
                            {{ $whatsappStats['last_sent'] ? $whatsappStats['last_sent']->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                </div>

                @if($whatsappStats['failed_week'] > 0)
                <a href="{{ route('admin.whatsapp_logs.index') }}" class="mt-4 block text-center text-xs font-medium text-slate-600 hover:text-slate-900">
                    View WhatsApp logs →
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- RENEWALS COMMAND TABLE --}}
    {{-- ============================================ --}}
    <div id="renewals-section" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-900">Renewals Command</h3>
                <p class="text-xs text-gray-500">Upcoming and overdue renewals requiring attention</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="filterRenewals('all')" class="renewal-filter active px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors" data-filter="all">
                    All ({{ $renewalsCommand->count() }})
                </button>
                <button onclick="filterRenewals('overdue')" class="renewal-filter px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors" data-filter="overdue">
                    Overdue ({{ $renewalsCommand->where('urgency', 'overdue')->count() }})
                </button>
                <button onclick="filterRenewals('critical')" class="renewal-filter px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors" data-filter="critical">
                    Critical ({{ $renewalsCommand->where('urgency', 'critical')->count() }})
                </button>
                <button onclick="filterRenewals('urgent')" class="renewal-filter px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors" data-filter="urgent">
                    This Week ({{ $renewalsCommand->where('urgency', 'urgent')->count() }})
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50">
                    <tr class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3 text-left">Patient</th>
                        <th class="px-5 py-3 text-left">Medicine</th>
                        <th class="px-5 py-3 text-left">Representative</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="renewals-tbody">
                    @forelse($renewalsCommand->take(15) as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors renewal-row" data-urgency="{{ $order->urgency }}">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-semibold text-gray-600">
                                    {{ substr($order->patient->name ?? 'U', 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 text-sm">{{ $order->patient->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->patient->country ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-sm text-gray-700">{{ $order->medicine->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-sm text-gray-600">{{ $order->representative->user->name ?? 'Unassigned' }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($order->days_remaining < 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                {{ abs($order->days_remaining) }}d overdue
                            </span>
                            @elseif($order->days_remaining <= 3)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                {{ $order->days_remaining }}d left
                            </span>
                            @elseif($order->days_remaining <= 7)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
                                {{ $order->days_remaining }}d left
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                {{ $order->days_remaining }}d left
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @if($order->patient->phone)
                                <a href="tel:{{ $order->patient->phone }}" class="p-1.5 rounded-lg text-gray-400 hover:text-orange-600 hover:bg-orange-50 transition-all" title="Call">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->patient->phone) }}" target="_blank" class="p-1.5 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-all" title="WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </a>
                                @endif
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-700 hover:text-white transition-all">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-orange-50 rounded-full text-hoot-maroon">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="font-medium text-gray-900">No upcoming renewals</p>
                            <p class="text-sm">All renewals are scheduled beyond 30 days</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($renewalsCommand->count() > 15)
        <div class="px-5 py-3 border-t border-gray-100 text-center">
            <a href="{{ route('admin.orders.index', ['renewal' => 'upcoming']) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                View all {{ $renewalsCommand->count() }} renewals →
            </a>
        </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- PERFORMANCE OVERVIEW --}}
    {{-- ============================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Representative Performance --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Representative Performance</h3>
                <p class="text-xs text-gray-500">Conversion rates and lead management</p>
            </div>
            <div class="divide-y divide-gray-50 max-h-[320px] overflow-y-auto">
                @forelse($repPerformance->take(6) as $index => $rep)
                <div class="px-5 py-3 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-gray-200 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500')) }}">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">{{ $rep['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $rep['country'] ?? 'N/A' }} · {{ $rep['total_leads'] }} leads</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold {{ $rep['conversion_rate'] >= 25 ? 'text-orange-600' : ($rep['conversion_rate'] >= 15 ? 'text-amber-600' : 'text-gray-600') }}">
                                {{ $rep['conversion_rate'] }}%
                            </div>
                            @if($rep['overdue_count'] > 0)
                            <div class="text-xs text-red-500">{{ $rep['overdue_count'] }} overdue</div>
                            @else
                            <div class="text-xs text-gray-400">{{ $rep['converted'] }} converted</div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1">
                        <div class="h-1 rounded-full transition-all {{ $rep['conversion_rate'] >= 25 ? 'bg-orange-500' : ($rep['conversion_rate'] >= 15 ? 'bg-amber-500' : 'bg-gray-400') }}"
                             style="width: {{ min($rep['conversion_rate'], 100) }}%"></div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-500 text-sm">No representatives found</div>
                @endforelse
            </div>
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                <a href="{{ route('admin.analytics.representatives') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                    View detailed performance →
                </a>
            </div>
        </div>

        {{-- Country Performance --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Country Performance</h3>
                <p class="text-xs text-gray-500">Lead distribution and conversion by region</p>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @php
                        $maxLeads = $countryPerformance->max('total_leads') ?: 1;
                        $colors = ['bg-blue-500', 'bg-orange-500', 'bg-purple-500', 'bg-amber-500', 'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500'];
                    @endphp
                    @forelse($countryPerformance->take(6) as $index => $country)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $colors[$index % count($colors)] }}"></span>
                                <span class="text-sm font-medium text-gray-700">{{ $country->country }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-gray-500">{{ $country->total_leads }} leads</span>
                                <span class="font-semibold {{ $country->conversion_rate >= 25 ? 'text-orange-600' : 'text-gray-600' }}">{{ $country->conversion_rate }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="{{ $colors[$index % count($colors)] }} h-2 rounded-full transition-all"
                                 style="width: {{ ($country->total_leads / $maxLeads) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-gray-500 text-sm">No country data available</div>
                    @endforelse
                </div>

                @if($countryPerformance->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        <span class="font-medium text-gray-700">{{ $countryPerformance->first()->country }}</span>
                        accounts for {{ $countryPerformance->sum('total_leads') > 0 ? round(($countryPerformance->first()->total_leads / $countryPerformance->sum('total_leads')) * 100) : 0 }}% of total leads
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MONTHLY TREND + MEDICINE DEMAND --}}
    {{-- ============================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Monthly Trend Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Monthly Trend</h3>
                <p class="text-xs text-gray-500">Patients and orders over the last 6 months</p>
            </div>
            <div class="p-5">
                <canvas id="monthlyTrendChart" height="160"></canvas>
            </div>
        </div>

        {{-- Medicine Demand --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Medicine Demand</h3>
                <p class="text-xs text-gray-500">Most ordered products</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($medicineDemand->take(5) as $medicine)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div>
                        <div class="font-medium text-gray-900 text-sm">{{ $medicine->name }}</div>
                        <div class="text-xs text-gray-500">{{ $medicine->active_orders }} active orders</div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ $medicine->total_orders }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-500 text-sm">No medicine data</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MARKETING TEAM PERFORMANCE --}}
    {{-- ============================================ --}}
    @if($marketingPerformance->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Marketing Team Performance</h3>
            <p class="text-xs text-gray-500">Lead generation quality and conversion</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50">
                    <tr class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3 text-left">Member</th>
                        <th class="px-5 py-3 text-center">Total Leads</th>
                        <th class="px-5 py-3 text-center">This Month</th>
                        <th class="px-5 py-3 text-center">Converted</th>
                        <th class="px-5 py-3 text-center">Conversion Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($marketingPerformance->take(5) as $member)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-xs font-semibold text-purple-600">
                                    {{ substr($member['name'], 0, 2) }}
                                </div>
                                <span class="font-medium text-gray-900 text-sm">{{ $member['name'] }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $member['total_leads'] }}</td>
                        <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $member['leads_this_month'] }}</td>
                        <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $member['converted'] }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $member['conversion_rate'] >= 25 ? 'bg-orange-100 text-orange-700' : ($member['conversion_rate'] >= 15 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $member['conversion_rate'] }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Trend Chart
    const ctx = document.getElementById('monthlyTrendChart');
    if (ctx) {
        const monthlyData = @json($monthlyTrend);
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: 'Patients',
                    data: monthlyData.map(d => d.patients),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                }, {
                    label: 'Orders',
                    data: monthlyData.map(d => d.orders),
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 11 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Renewals filter functionality
    function filterRenewals(filter) {
        const rows = document.querySelectorAll('.renewal-row');
        const buttons = document.querySelectorAll('.renewal-filter');

        buttons.forEach(btn => {
            btn.classList.remove('active', 'bg-slate-100', 'text-slate-700');
            btn.classList.add('bg-gray-50', 'text-gray-600');
        });

        const activeBtn = document.querySelector(`[data-filter="${filter}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-slate-100', 'text-slate-700');
            activeBtn.classList.remove('bg-gray-50', 'text-gray-600');
        }

        rows.forEach(row => {
            const urgency = row.dataset.urgency;
            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'overdue' && urgency === 'overdue') {
                row.style.display = '';
            } else if (filter === 'critical' && (urgency === 'overdue' || urgency === 'critical')) {
                row.style.display = '';
            } else if (filter === 'urgent' && urgency === 'urgent') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection
