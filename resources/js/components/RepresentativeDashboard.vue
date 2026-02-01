<template>
    <div class="space-y-6 sm:space-y-8 motion-reduce:animate-none">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-purple-800 to-indigo-900 rounded-3xl p-5 sm:p-8 overflow-hidden shadow-lg sm:shadow-2xl text-white motion-reduce:transition-none">
            <div class="hidden sm:block absolute top-0 right-0 h-full w-1/2 opacity-20 bg-cover bg-right transform translate-x-12" style="background-image: url('/images/dashboard-pattern.svg');"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between pointer-events-none md:pointer-events-auto">
                <div class="space-y-3 sm:space-y-4 md:w-1/2">
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold tracking-tight">
                        {{ greeting }},<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-emerald-300">
                             {{ representative.user.name }}!
                        </span>
                    </h1>
                    <p class="text-sm sm:text-lg text-purple-100 opacity-90">
                        Ready to achieve new heights today? Your patients are waiting for your care.
                    </p>
                    <div class="pt-4 pointer-events-auto">
                        <a href="/portal/patients/create" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 bg-teal-500 hover:bg-teal-400 text-white font-bold rounded-full shadow-sm sm:shadow-lg transition-all sm:transform sm:hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Patient
                        </a>
                    </div>
                </div>
                
                <div class="hidden md:block md:w-2/5 mt-6 md:mt-0 transform hover:scale-105 transition-transform duration-500">
                     <!-- Illustration -->
                     <img src="https://motiongility.com/wp-content/uploads/2023/05/What-is-Brand-Value.png" alt="Team Success" class="w-full h-auto drop-shadow-2xl">
                </div>
            </div>
             <!-- Decorative Circles -->
            <div class="absolute -bottom-10 -left-10 w-32 sm:w-40 h-32 sm:h-40 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob motion-reduce:animate-none"></div>
            <div class="absolute top-0 -right-10 w-32 sm:w-40 h-32 sm:h-40 bg-teal-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 motion-reduce:animate-none"></div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <!-- Total Patients -->
            <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm sm:shadow-xl border border-gray-100 hover:shadow-2xl hover:border-blue-200 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">My Patients</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-gray-800 mt-2 group-hover:text-blue-600 transition-colors">{{ stats.total_patients }}</p>
                    </div>
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl sm:text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                        👤
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span class="text-green-500 font-bold mr-1">↑ Active</span> currently managed
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm sm:shadow-xl border border-gray-100 hover:shadow-2xl hover:border-orange-200 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Active Orders</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-gray-800 mt-2 group-hover:text-orange-600 transition-colors">{{ stats.active_orders }}</p>
                    </div>
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xl sm:text-2xl group-hover:bg-orange-600 group-hover:text-white transition-all shadow-sm">
                        📋
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                     Orders currently in progress
                </div>
            </div>

            <!-- Renewals -->
            <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm sm:shadow-xl border border-gray-100 hover:shadow-2xl hover:border-green-200 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Renewals (30 Days)</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-gray-800 mt-2 group-hover:text-green-600 transition-colors">{{ upcomingRenewals.length }}</p>
                    </div>
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl sm:text-2xl group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                        🔄
                    </div>
                </div>
                 <div class="mt-4 text-sm text-gray-500">
                     Actions needed this month
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- Pending Leads Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 lg:col-span-2 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Pending Leads</h3>
                    </div>
                    <a href="/portal/leads" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center group">
                        View All
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                
                <!-- Mobile Cards -->
                <div class="sm:hidden p-4 space-y-3">
                    <div v-for="lead in pendingLeads" :key="lead.id" class="border border-gray-100 rounded-lg p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-bold text-gray-900">{{ lead.name }}</p>
                                <p class="text-xs text-gray-500">{{ lead.country }}</p>
                            </div>
                            <span :class="getStatusClass(lead.lead_status)" class="px-2 py-1 text-xs font-bold rounded-full">
                                {{ capitalize(lead.lead_status) }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">Assigned: {{ formatDate(lead.assigned_at) }}</div>
                        <div class="mt-2 text-xs text-gray-500">
                            <div v-if="lead.latest_activity">
                                <span class="font-semibold text-gray-700">{{ capitalize(lead.latest_activity.type) }}</span>
                                <span class="block text-gray-400">{{ formatDate(lead.latest_activity.created_at) }}</span>
                            </div>
                            <span v-else class="text-gray-400 italic">No activity</span>
                        </div>
                        <div class="mt-3">
                            <a :href="`/portal/leads`" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">Open</a>
                        </div>
                    </div>
                    <div v-if="pendingLeads.length === 0" class="text-center text-gray-500 py-6">
                        <p class="text-lg">🎉 You're all caught up!</p>
                        <p class="text-sm">No pending leads at the moment.</p>
                    </div>
                </div>

                <!-- Table (Desktop) -->
                <div class="hidden sm:block relative overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4">Lead Name</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Assigned</th>
                                <th class="px-6 py-4">Last Activity</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                             <tr v-for="lead in pendingLeads" :key="lead.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ lead.name }}</div>
                                    <div class="text-sm text-gray-500">{{ lead.country }}</div>
                                </td>
                                <td class="px-6 py-4">
                                     <span :class="getStatusClass(lead.lead_status)" class="px-3 py-1 text-xs font-bold rounded-full">
                                        {{ capitalize(lead.lead_status) }}
                                     </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ formatDate(lead.assigned_at) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                     <div v-if="lead.latest_activity">
                                         <span class="font-semibold text-gray-700">{{ capitalize(lead.latest_activity.type) }}</span>
                                         <span class="text-gray-400 text-xs block">{{ formatDate(lead.latest_activity.created_at) }}</span>
                                     </div>
                                     <span v-else class="text-gray-400 italic">No activity</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a :href="`/portal/leads`" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg hover:bg-indigo-100 transition-colors">
                                        Open
                                    </a>
                                </td>
                             </tr>
                             <tr v-if="pendingLeads.length === 0">
                                 <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                     <p class="text-lg">🎉 You're all caught up!</p>
                                     <p class="text-sm">No pending leads at the moment.</p>
                                 </td>
                             </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Bottom Row: Recent & Renewals -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
              <!-- Upcoming Renewals -->
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
                 <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="mr-2 p-1.5 bg-green-100 text-green-600 rounded-md">📅</span> Upcoming Renewals
                 </h3>
                 <div class="space-y-4">
                      <div v-for="order in upcomingRenewals" :key="order.id" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 rounded-lg border border-gray-100 hover:border-green-200 hover:shadow-md transition-all">
                          <div>
                              <p class="font-bold text-gray-900">{{ order.patient.name }}</p>
                              <p class="text-xs text-gray-500">{{ order.medicine.name }}</p>
                          </div>
                          <div class="text-left sm:text-right">
                               <p class="text-sm font-bold text-green-600">
                                   {{ formatDate(order.expected_renewal_date) }}
                               </p>
                               <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Due soon</span>
                          </div>
                      </div>
                       <p v-if="upcomingRenewals.length === 0" class="text-gray-400 text-sm text-center py-4">No upcoming renewals.</p>
                 </div>
              </div>

               <!-- Recent Orders -->
               <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
                 <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="mr-2 p-1.5 bg-orange-100 text-orange-600 rounded-md">📦</span> Recent Orders
                 </h3>
                 <div class="space-y-4">
                       <div v-for="order in recentOrders" :key="order.id" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 rounded-lg border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all">
                          <div>
                              <p class="font-bold text-gray-900">{{ order.patient.name }}</p>
                              <p class="text-xs text-gray-500">{{ order.medicine.name }} ({{ order.packs_ordered }} packs)</p>
                          </div>
                          <div class="text-left sm:text-right">
                               <p class="text-sm font-bold text-gray-600">
                                   {{ formatDate(order.created_at) }}
                               </p>
                          </div>
                      </div>
                      <p v-if="recentOrders.length === 0" class="text-gray-400 text-sm text-center py-4">No recent orders found.</p>
                 </div>
              </div>
         </div>

    </div>
</template>

<script>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);

export default {
    props: {
        representative: Object,
        stats: Object,
        upcomingRenewals: Array,
        overdueRenewals: Array,
        recentOrders: Array,
        pendingLeads: Array
    },
    computed: {
        greeting() {
            const hour = new Date().getHours();
            if (hour < 12) return 'Good Morning';
            if (hour < 17) return 'Good Afternoon';
            return 'Good Evening';
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return 'N/A';
            return dayjs(date).format('D MMM YYYY'); // 12 Dec 2026
        },
        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        getStatusClass(status) {
            const classes = {
                'new': 'bg-red-100 text-red-700',
                'assigned': 'bg-blue-100 text-blue-700',
                'contacted': 'bg-purple-100 text-purple-700',
                'negotiating': 'bg-amber-100 text-amber-700',
                'converted': 'bg-green-100 text-green-700',
                'lost': 'bg-gray-100 text-gray-700'
            };
            return classes[status] || 'bg-gray-100 text-gray-700';
        }
    }
}
</script>

<style scoped>
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.fade-in-up {
    animation: fadeInUp 0.5s ease-out;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
