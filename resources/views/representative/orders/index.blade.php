<x-app-layout>
    <div class="space-y-4">
        <!-- Page Header -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-corp-900">Orders</h1>
                <p class="text-sm text-corp-400 mt-0.5">{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }} total</p>
            </div>
            <a href="{{ route('representative.orders.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-corp-900 text-white rounded-xl text-sm font-medium hover:bg-hoot-dark transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Order
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-corp-100 p-3 shadow-sm">
            <form action="{{ route('representative.orders.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient or medicine..." class="w-full pl-10 pr-3 py-2.5 border border-corp-200 rounded-xl text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                </div>
                <select name="status" class="w-full sm:w-auto border border-corp-200 rounded-xl py-2.5 px-3 text-sm text-corp-600 focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <select name="renewal_filter" class="w-full sm:w-auto border border-corp-200 rounded-xl py-2.5 px-3 text-sm text-corp-600 focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                    <option value="">Renewal</option>
                    <option value="overdue" {{ request('renewal_filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="week" {{ request('renewal_filter') == 'week' ? 'selected' : '' }}>Next 7 Days</option>
                    <option value="month" {{ request('renewal_filter') == 'month' ? 'selected' : '' }}>Next 30 Days</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-corp-700 hover:bg-corp-800 text-white rounded-xl text-sm font-medium transition-colors">
                    Filter
                </button>
                @if(request('search') || request('status') || request('renewal_filter'))
                    <a href="{{ route('representative.orders.index') }}" class="px-4 py-2.5 bg-corp-50 hover:bg-corp-100 text-corp-600 rounded-xl text-sm font-medium transition-colors border border-corp-200 text-center">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Order Cards -->
        <div class="space-y-3">
            @forelse($orders as $order)
                @php
                    $statusColors = [
                        'active' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'completed' => 'bg-corp-50 text-corp-600 border-corp-200',
                        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                    ];
                    $statusColor = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                    $isUrgent = $order->status === 'active' && $order->days_until_renewal <= 7;
                @endphp
                <div class="bg-white rounded-xl border {{ $isUrgent ? 'border-red-200' : 'border-corp-100' }} overflow-hidden hover:shadow-sm transition-shadow">
                    <div class="p-4">
                        <!-- Top row: Patient + Status -->
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-corp-900 truncate">{{ $order->patient->name }}</h3>
                                <p class="text-sm text-corp-400 flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $order->patient->phone }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusColor }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Medicine & details -->
                        <div class="bg-corp-50 rounded-lg px-3 py-2.5 mb-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-corp-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    <span class="text-sm font-medium text-corp-700">{{ $order->medicine->name }}</span>
                                </div>
                                <span class="text-sm text-corp-500">{{ $order->packs_ordered }} packs</span>
                            </div>
                        </div>

                        <!-- Date info -->
                        <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                            <div>
                                <span class="text-xs text-corp-400 block">Start Date</span>
                                <span class="text-corp-700 font-medium flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $order->treatment_start_date->format('d M Y') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-corp-400 block">Renewal Date</span>
                                <span class="font-medium flex items-center gap-1 mt-0.5 {{ $isUrgent ? 'text-red-600' : 'text-corp-700' }}">
                                    <svg class="w-3.5 h-3.5 {{ $isUrgent ? 'text-red-400' : 'text-corp-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $order->expected_renewal_date->format('d M Y') }}
                                    @if($order->status === 'active')
                                        <span class="text-xs {{ $isUrgent ? 'text-red-500 font-bold' : 'text-corp-400' }}">({{ $order->days_until_renewal }}d)</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('representative.orders.edit', $order) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-corp-50 hover:bg-corp-100 text-corp-600 border border-corp-200 rounded-lg font-medium text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('representative.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg font-medium text-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-corp-100 p-8 text-center">
                    <div class="w-16 h-16 bg-corp-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="font-semibold text-corp-900 mb-1">No orders found</h3>
                    <p class="text-sm text-corp-400">Try adjusting your filters or create a new order.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="bg-white rounded-xl border border-corp-100 px-4 py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
