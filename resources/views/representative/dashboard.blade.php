<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Representative Dashboard</h3>
        <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
        <!-- My Patients -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs cursor-pointer hover:bg-gray-50 hover:shadow-md transition-all">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">My Patients</p>
                <p class="text-lg font-semibold text-gray-700">{{ $stats['total_patients'] }}</p>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs cursor-pointer hover:bg-gray-50 hover:shadow-md transition-all">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Active Orders</p>
                <p class="text-lg font-semibold text-gray-700">{{ $stats['active_orders'] }}</p>
            </div>
        </div>
        
         <!-- Renewals This Month -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs cursor-pointer hover:bg-gray-50 hover:shadow-md transition-all">
             <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Renewals (Next 30 Days)</p>
                <p class="text-lg font-semibold text-gray-700">{{ $upcomingRenewals->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Upcoming Renewals & Recent Orders -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Upcoming Renewals -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="flex items-center justify-between p-4 border-b">
                <h4 class="text-lg font-semibold text-gray-600">Upcoming Renewals</h4>
                <a href="{{ route('representative.orders.index', ['renewal_filter' => 'month']) }}" class="text-sm text-green-600 hover:underline">View All</a>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Patient</th>
                            <th class="px-4 py-3">Medicine</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($upcomingRenewals as $order)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">
                                <p class="font-semibold">{{ $order->patient->name }}</p>
                                <p class="text-xs text-gray-600">{{ $order->patient->phone }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $order->medicine->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $order->expected_renewal_date->format('d M Y') }}
                                <br>
                                <span class="text-xs {{ $order->days_until_renewal <= 7 ? 'text-red-600 font-bold' : 'text-orange-500' }}">
                                    ({{ $order->days_until_renewal }} days)
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                    {{ ucfirst($order->renewal_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-sm text-center text-gray-500">
                                No upcoming renewals in the next 30 days.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="flex items-center justify-between p-4 border-b">
                <h4 class="text-lg font-semibold text-gray-600">Recent Orders</h4>
                <a href="{{ route('representative.orders.index') }}" class="text-sm text-green-600 hover:underline">View All</a>
            </div>
             <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Patient</th>
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                         @forelse($recentOrders as $order)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">
                                <p class="font-semibold">{{ $order->patient->name }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $order->medicine->name }} ({{ $order->packs_ordered }})
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-sm text-center text-gray-500">
                                No recent orders.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
