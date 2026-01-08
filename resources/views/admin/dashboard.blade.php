<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Dashboard</h3>
        <p class="mt-1 text-sm text-gray-500">Overview of Hootone One platform</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Total Representatives -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs cursor-pointer hover:bg-gray-50 hover:shadow-md transition-all">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Representatives</p>
                <p class="text-lg font-semibold text-gray-700">{{ $stats['total_representatives'] }}</p>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs cursor-pointer hover:bg-gray-50 hover:shadow-md transition-all">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Patients</p>
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

        <!-- Total Medicines -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs cursor-pointer hover:bg-gray-50 hover:shadow-md transition-all">
            <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.816 14.769 2.032 18 4.828 18h10.344c2.796 0 4.012-3.231 2.12-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a4 4 0 00-2.17-.102l1.027-1.028A3 3 0 009 8.172zM5 16a1 1 0 011-1h6a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Medicines/Treatments</p>
                <p class="text-lg font-semibold text-gray-700">{{ $stats['total_medicines'] }}</p>
            </div>
        </div>
    </div>

    <!-- Charts & Upcoming Renewals -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Upcoming Renewals -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="flex items-center justify-between p-4 border-b">
                <h4 class="text-lg font-semibold text-gray-600">Upcoming Renewals (Next 30 Days)</h4>
                <a href="{{ route('admin.orders.index', ['renewal_filter' => 'month']) }}" class="text-sm text-green-600 hover:underline">View All</a>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Patient</th>
                            <th class="px-4 py-3">Medicine</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Representative</th>
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
                             <td class="px-4 py-3 text-sm">
                                {{ $order->representative->user->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">
                                No upcoming renewals in the next 30 days.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Analytics Chart -->
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">Orders by Country</h4>
            <canvas id="countryChart"></canvas>
        </div>
    </div>
    
    <!-- Scripts for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('countryChart').getContext('2d');
            const countryData = @json($ordersByCountry);
            
            const labels = countryData.map(item => item.country);
            const data = countryData.map(item => item.total);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#1B4332', '#2D6A4F', '#40916C', '#52B788', '#74C69D',
                            '#95D5B2', '#B7E4C7', '#D8F3DC', '#081C15', '#1B4332'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
