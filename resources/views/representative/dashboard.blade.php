<x-app-layout>
    <div id="vue-app">
        <representative-dashboard 
            :representative='@json($representative->load("user"))'
            :stats='@json($stats)'
            :upcoming-renewals='@json($upcomingRenewals)'
            :overdue-renewals='@json($overdueRenewals)'
            :recent-orders='@json($recentOrders)'
            :pending-leads='@json($pendingLeads)'
        ></representative-dashboard>
    </div>
</x-app-layout>
