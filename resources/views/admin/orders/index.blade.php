<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-corp-900">Orders</h3>
                <p class="mt-1 text-sm text-corp-400">Manage medicine orders and renewals</p>
            </div>
            <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-corp-600 hover:bg-corp-700 rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Order
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-corp-100 shadow-sm mb-6">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="p-4 sm:p-5">
                    <!-- Search & Actions Row -->
                    <div class="flex flex-col md:flex-row gap-3 mb-4">
                        <div class="flex-1">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-corp-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name or phone..." class="w-full pl-10 pr-4 py-2.5 border border-corp-200 rounded-xl text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 transition-colors bg-corp-50/50">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 md:flex-none px-5 py-2.5 bg-corp-900 text-white text-sm font-semibold rounded-xl hover:bg-corp-800 transition-colors shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter
                            </button>
                            <button type="submit" name="export" value="true" class="flex-1 md:flex-none px-5 py-2.5 bg-white text-corp-600 text-sm font-semibold border border-corp-200 rounded-xl hover:bg-corp-50 transition-colors shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-1.5 text-hoot-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Export
                            </button>
                            @if(request()->anyFilled(['search', 'representative_id', 'country', 'medicine_id', 'status', 'renewal_filter']))
                                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 bg-corp-50 text-corp-500 text-sm font-medium rounded-xl hover:bg-corp-100 transition-colors border border-corp-200 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Clear
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Representative</label>
                            <select name="representative_id" class="w-full border-corp-200 rounded-lg text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 h-10 bg-white">
                                <option value="">All Reps</option>
                                @foreach($representatives as $rep)
                                    <option value="{{ $rep->id }}" {{ request('representative_id') == $rep->id ? 'selected' : '' }}>{{ $rep->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Country</label>
                            <select name="country" class="w-full border-corp-200 rounded-lg text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 h-10 bg-white">
                                <option value="">All Countries</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Medicine</label>
                            <select name="medicine_id" class="w-full border-corp-200 rounded-lg text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 h-10 bg-white">
                                <option value="">All Medicines</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ request('medicine_id') == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" class="w-full border-corp-200 rounded-lg text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 h-10 bg-white">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Renewal</label>
                            <select name="renewal_filter" class="w-full border-corp-200 rounded-lg text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 h-10 bg-white">
                                <option value="">Any Date</option>
                                <option value="overdue" {{ request('renewal_filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="week" {{ request('renewal_filter') == 'week' ? 'selected' : '' }}>Next 7 Days</option>
                                <option value="month" {{ request('renewal_filter') == 'month' ? 'selected' : '' }}>Next 30 Days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden lg:block bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-corp-50 border-b border-corp-100">
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Medicine / Packs</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Start Date</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Renewal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-corp-600 uppercase tracking-wider">Rep</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-corp-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-corp-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-corp-50/50 transition-colors">
                            <!-- Patient Name -->
                            <td class="px-4 py-3">
                                <p class="font-semibold text-corp-900 text-sm">{{ $order->patient->name }}</p>
                            </td>
                            <!-- Contact Actions: Copy, WhatsApp, Call -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    <!-- Copy Phone -->
                                    <button onclick="copyPhone('{{ $order->patient->phone }}', this)" title="Copy {{ $order->patient->phone }}"
                                        class="group relative w-8 h-8 inline-flex items-center justify-center bg-corp-50 hover:bg-corp-100 text-corp-500 hover:text-corp-700 border border-corp-200 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                        <svg class="w-3.5 h-3.5 check-icon hidden text-hoot-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <!-- WhatsApp -->
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $order->patient->phone) }}" target="_blank" title="WhatsApp"
                                       class="w-8 h-8 inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    </a>
                                    <!-- Call -->
                                    <a href="tel:{{ $order->patient->phone }}" title="Call"
                                       class="w-8 h-8 inline-flex items-center justify-center bg-corp-600 hover:bg-corp-700 text-white rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </a>
                                </div>
                            </td>
                            <!-- Medicine -->
                            <td class="px-4 py-3 text-sm">
                                <p class="text-corp-900">{{ $order->medicine->name }}</p>
                                <p class="text-xs text-corp-400">{{ $order->packs_ordered }} packs</p>
                            </td>
                            <!-- Start Date -->
                            <td class="px-4 py-3 text-sm text-corp-600">
                                {{ $order->treatment_start_date->format('d M Y') }}
                            </td>
                            <!-- Renewal Date -->
                            <td class="px-4 py-3 text-sm">
                                <p class="text-corp-900">{{ $order->expected_renewal_date->format('d M Y') }}</p>
                                @if($order->status === 'active')
                                    @if($order->days_until_renewal < 0)
                                        <span class="text-xs font-bold text-red-600">{{ abs($order->days_until_renewal) }}d overdue</span>
                                    @elseif($order->days_until_renewal <= 7)
                                        <span class="text-xs font-bold text-amber-600">{{ $order->days_until_renewal }}d left</span>
                                    @else
                                        <span class="text-xs text-corp-400">{{ $order->days_until_renewal }} days</span>
                                    @endif
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="px-4 py-3">
                                @if($order->status == 'active')
                                    <span class="px-2.5 py-1 text-xs font-bold text-orange-700 bg-orange-50 border border-orange-200 rounded-full">Active</span>
                                @elseif($order->status == 'completed')
                                    <span class="px-2.5 py-1 text-xs font-bold text-corp-600 bg-corp-50 border border-corp-200 rounded-full">Completed</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-bold text-red-600 bg-red-50 border border-red-200 rounded-full">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <!-- Rep -->
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-corp-900">{{ $order->representative->user->name }}</p>
                                <p class="text-xs text-corp-400">{{ $order->representative->country }}</p>
                            </td>
                            <!-- Actions -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.orders.edit', $order) }}" title="Edit"
                                       class="w-8 h-8 inline-flex items-center justify-center text-corp-500 hover:text-corp-700 hover:bg-corp-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                            class="w-8 h-8 inline-flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-corp-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm text-corp-400">No orders found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
            <div class="px-4 py-3 border-t border-corp-100 bg-corp-50/50">
                {{ $orders->links() }}
            </div>
            @endif
        </div>

        <!-- Mobile Cards (visible on mobile/tablet, hidden on desktop) -->
        <div class="lg:hidden space-y-3">
            @forelse($orders as $order)
            <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
                <!-- Card Header: Patient + Status -->
                <div class="p-4 pb-3">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-corp-900 text-base truncate">{{ $order->patient->name }}</p>
                            <p class="text-sm text-corp-400 mt-0.5">{{ $order->patient->phone }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($order->status == 'active')
                                <span class="px-2.5 py-1 text-xs font-bold text-orange-700 bg-orange-50 border border-orange-200 rounded-full">Active</span>
                            @elseif($order->status == 'completed')
                                <span class="px-2.5 py-1 text-xs font-bold text-corp-600 bg-corp-50 border border-corp-200 rounded-full">Completed</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold text-red-600 bg-red-50 border border-red-200 rounded-full">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Actions Row -->
                    <div class="flex items-center gap-2 mb-3">
                        <!-- Copy Phone -->
                        <button onclick="copyPhone('{{ $order->patient->phone }}', this)"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-corp-50 hover:bg-corp-100 text-corp-600 border border-corp-200 rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-3.5 h-3.5 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <svg class="w-3.5 h-3.5 check-icon hidden text-hoot-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="copy-text">Copy</span>
                        </button>
                        <!-- WhatsApp -->
                        <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $order->patient->phone) }}" target="_blank"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            WhatsApp
                        </a>
                        <!-- Call -->
                        <a href="tel:{{ $order->patient->phone }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-corp-600 hover:bg-corp-700 text-white rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Call
                        </a>
                    </div>

                    <!-- Order Details Grid -->
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div>
                            <span class="text-xs text-corp-400 uppercase tracking-wider">Medicine</span>
                            <p class="font-medium text-corp-900">{{ $order->medicine->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-corp-400 uppercase tracking-wider">Packs</span>
                            <p class="font-medium text-corp-900">{{ $order->packs_ordered }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-corp-400 uppercase tracking-wider">Start</span>
                            <p class="font-medium text-corp-700">{{ $order->treatment_start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-corp-400 uppercase tracking-wider">Renewal</span>
                            <p class="font-medium text-corp-900">{{ $order->expected_renewal_date->format('d M Y') }}</p>
                            @if($order->status === 'active')
                                @if($order->days_until_renewal < 0)
                                    <span class="text-xs font-bold text-red-600">{{ abs($order->days_until_renewal) }}d overdue</span>
                                @elseif($order->days_until_renewal <= 7)
                                    <span class="text-xs font-bold text-amber-600">{{ $order->days_until_renewal }}d left</span>
                                @else
                                    <span class="text-xs text-corp-400">{{ $order->days_until_renewal }} days</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Footer: Rep + Actions -->
                <div class="px-4 py-2.5 bg-corp-50/50 border-t border-corp-100 flex items-center justify-between">
                    <div class="text-xs">
                        <span class="font-semibold text-corp-700">{{ $order->representative->user->name }}</span>
                        <span class="text-corp-400 mx-1">&middot;</span>
                        <span class="text-corp-400">{{ $order->representative->country }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.orders.edit', $order) }}" title="Edit"
                           class="w-8 h-8 inline-flex items-center justify-center text-corp-500 hover:text-corp-700 hover:bg-corp-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete"
                                class="w-8 h-8 inline-flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border border-corp-100 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-corp-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-sm text-corp-400">No orders found.</p>
            </div>
            @endforelse

            @if($orders->hasPages())
            <div class="bg-white rounded-xl border border-corp-100 shadow-sm px-4 py-3">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Copy Phone Script -->
    <script>
    function copyPhone(phone, btn) {
        navigator.clipboard.writeText(phone).then(() => {
            const copyIcon = btn.querySelector('.copy-icon');
            const checkIcon = btn.querySelector('.check-icon');
            const copyText = btn.querySelector('.copy-text');

            if (copyIcon) copyIcon.classList.add('hidden');
            if (checkIcon) checkIcon.classList.remove('hidden');
            if (copyText) copyText.textContent = 'Copied!';

            btn.classList.add('ring-2', 'ring-orange-300');

            setTimeout(() => {
                if (copyIcon) copyIcon.classList.remove('hidden');
                if (checkIcon) checkIcon.classList.add('hidden');
                if (copyText) copyText.textContent = 'Copy';
                btn.classList.remove('ring-2', 'ring-orange-300');
            }, 1500);
        });
    }
    </script>
</x-app-layout>
