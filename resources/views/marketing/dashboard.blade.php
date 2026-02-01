<x-app-layout>
    <div id="vue-app">
        <marketing-dashboard
            :stats="{{ json_encode($stats) }}"
            :incoming-leads="{{ json_encode($incomingLeads) }}"
            :recent-activities="{{ json_encode($recentActivities) }}"
            :score="{{ json_encode($score) }}"
            :velocity-data="{{ json_encode($velocityData) }}"
            :quality-data="{{ json_encode($qualityData) }}"
        ></marketing-dashboard>
    </div>
</x-app-layout>
