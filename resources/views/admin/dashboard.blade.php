<x-app-layout>
    <div class="mb-8">
        <h3 class="text-3xl font-bold text-gray-800">Dashboard</h3>
        <p class="mt-2 text-sm text-gray-500">Overview of Hootone One platform</p>
    </div>

    <div id="vue-app">
        <admin-dashboard
            :stats='@json($stats)'
            :upcoming-renewals='@json($upcomingRenewals)'
            :overdue-renewals='@json($overdueRenewals)'
            :recent-orders='@json($recentOrders)'
            :orders-by-country='@json($ordersByCountry)'
        ></admin-dashboard>
    </div>
</x-app-layout>
