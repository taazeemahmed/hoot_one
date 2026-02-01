<template>
    <div class="space-y-8 font-sans text-gray-800">
        <!-- Top Summary Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Card 1: Total Patients (Dark Teal Gradient) -->
            <div class="relative flex flex-col justify-between p-6 overflow-hidden transition-all duration-200 shadow-lg rounded-2xl group hover:-translate-y-1"
                 style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);">
                <!-- Background decoration -->
                <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-2 -translate-y-2">
                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                
                <div class="relative z-10">
                    <div class="flex items-center text-teal-100 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-sm font-medium tracking-wide">Total Patients</span>
                    </div>
                    <div class="flex items-end items-baseline my-2">
                        <h3 class="text-4xl font-bold text-white mr-2 tracking-tight">{{ stats.total_patients }}</h3>
                        <span class="text-sm font-medium text-teal-200 bg-white/10 px-2 py-0.5 rounded backdrop-blur-sm">+12 this month</span>
                    </div>
                </div>
                <div class="relative z-10 mt-4">
                     <a href="/admin/patients" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-white bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg transition-colors">
                        <span class="mr-2">↗</span> View all patients
                    </a>
                </div>
            </div>

            <!-- Card 2: Active Orders -->
            <div class="flex flex-col justify-between p-6 bg-white shadow-sm hover:shadow-md transition-all duration-200 rounded-2xl border border-gray-100 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                         <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Orders</p>
                            <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ stats.active_orders }}</h3>
                        </div>
                    </div>
                    <span class="text-emerald-500 bg-emerald-50 p-2 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </span>
                </div>
                <div class="flex items-center text-sm text-gray-500 pl-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></span>
                    <span>+{{ stats.pending_orders || 0 }} pending approval</span>
                </div>
            </div>

            <!-- Card 3: Needs Attention -->
            <div class="flex flex-col justify-between p-6 bg-white shadow-sm hover:shadow-md transition-all duration-200 rounded-2xl border border-gray-100 relative overflow-hidden hover:-translate-y-1">
                 <!-- Subtle background blur decoration -->
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-orange-50 rounded-full blur-2xl opacity-60"></div>

                <div class="flex items-start justify-between mb-4 relative z-10">
                   <div class="flex items-center">
                        <div class="p-3 bg-orange-50 rounded-xl text-orange-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Needs Attention</p>
                            <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ urgentItems.length }}</h3>
                        </div>
                   </div>
                </div>
                <div class="relative z-10 flex items-center text-sm text-gray-500 pl-1">
                    <span class="font-bold text-red-500 mr-1 bg-red-50 px-1.5 py-0.5 rounded">{{ overdueRenewals.length }}</span> overdue renewals
                </div>
            </div>

            <!-- Card 4: Representatives -->
            <div class="flex flex-col justify-between p-6 bg-white shadow-sm hover:shadow-md transition-all duration-200 rounded-2xl border border-gray-100 relative overflow-hidden hover:-translate-y-1">
                <!-- Subtle background blur decoration -->
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60"></div>
                
                <div class="flex items-start justify-between mb-4 relative z-10">
                     <div class="flex items-center">
                         <div class="p-3 bg-blue-50 rounded-xl text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-500">Representatives</p>
                            <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ stats.total_representatives }}</h3>
                        </div>
                     </div>
                </div>
                <div class="mt-2 relative z-10">
                     <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors inline-block">Manage team</a>
                </div>
            </div>
        </div>

        <!-- Main Content Area: Urgent List + Chart -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Needs Attention Today - Clean List -->
            <div class="xl:col-span-2 space-y-4">
                <div class="flex items-center space-x-2 pl-1">
                    <div class="p-1 bg-amber-100 rounded text-amber-600">
                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Needs Attention Today</h3>
                    <div class="group relative">
                        <span class="text-gray-300 text-sm cursor-pointer hover:text-gray-500 transition-colors">ⓘ</span>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-gray-800 text-white text-xs rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            High priority items requiring action within 72 hours.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                     <div v-if="urgentItems.length === 0" class="p-12 text-center text-gray-500">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-green-50 rounded-full text-green-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-lg font-medium text-gray-900">All caught up!</p>
                        <p>No urgent issues today.</p>
                    </div>

                    <div v-else v-for="item in urgentItems" :key="item.id" class="p-5 flex flex-col md:flex-row md:items-center justify-between group hover:bg-gray-50 transition-colors">
                        <!-- Left: Info -->
                        <div class="flex items-start space-x-5 mb-4 md:mb-0">
                            <!-- Avatar Placeholder -->
                            <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold bg-gray-100 text-gray-500 border-2 border-white shadow-sm ring-1 ring-gray-100">
                                {{ getInitials(item.patient?.name) }}
                            </div>
                            
                            <div>
                                <h4 class="text-base font-bold text-gray-900 leading-tight mb-1">
                                    {{ item.patient?.name }}
                                    <span class="ml-2 text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full inline-block">{{ item.medicine?.name }}</span>
                                </h4>
                                
                                <p class="text-sm mt-1.5 flex items-center">
                                    <span v-if="item.isOverdue" class="text-red-500 font-bold flex items-center">
                                        Overdue by {{ Math.abs(item.days_until_renewal) }} days
                                    </span>
                                    <span v-else class="text-amber-600 font-bold flex items-center">
                                        Due in {{ item.days_until_renewal }} days
                                    </span>
                                    <span class="text-gray-300 mx-2">|</span>
                                    <span class="text-gray-500 text-xs font-medium">Rep: {{ item.representative?.user?.name }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center space-x-3 self-end md:self-center bg-white p-1 rounded-lg">
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider rounded-md border border-red-100 mr-2">Urgent</span>
                            
                            <a :href="item.patient?.phone ? `tel:${item.patient.phone}` : '#'" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-emerald-500 transition-all border border-transparent hover:shadow-md" title="Call">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </a>
                            
                            <a :href="item.patient?.phone ? `https://wa.me/${item.patient.phone}` : '#'" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-emerald-500 transition-all border border-transparent hover:shadow-md" title="WhatsApp">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </a>

                             <button @click="markAsDone(item.id)" class="w-9 h-9 flex items-center justify-center rounded-lg text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white transition-all shadow-sm hover:shadow-md" title="Mark Done">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            
                             <button class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-300 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                        </div>
                    </div>

                     <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl text-center">
                        <a href="#" class="inline-flex items-center text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-gray-800 transition-colors">
                            View all critical actions <span class="ml-2 text-lg leading-none">→</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between h-auto">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Orders by Country</h3>
                    <p class="text-sm text-gray-500 mb-6">Distribution of patient orders globally.</p>
                </div>

                <div class="relative w-full aspect-square max-h-64 mx-auto mb-6">
                    <canvas id="dashboardCountryChart"></canvas>
                    <!-- Center Text Absolute -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-4xl font-bold text-teal-900 tracking-tighter">{{ stats.total_orders }}</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Orders</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                     <div v-for="(item, index) in topCountries" :key="index" class="flex items-center justify-between text-sm group cursor-default">
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full mr-3 ring-2 ring-white transition-transform group-hover:scale-125" :style="{ backgroundColor: chartColors[index % chartColors.length] }"></span>
                            <span class="text-gray-600 font-medium group-hover:text-gray-900 transition-colors">{{ item.country }}</span>
                        </div>
                        <span class="font-bold text-gray-800">{{ item.total }} <span class="text-gray-400 font-normal text-xs">orders</span></span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex items-start gap-2">
                    <span class="text-yellow-500 mt-0.5">💡</span>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        <span class="font-semibold text-gray-700">Insight:</span> {{ topCode }} accounts for {{ topPercent }}% of renewals this month.
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent & Upcoming Renewals Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Recent & Upcoming Renewals</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 bg-gray-50/30">
                            <th class="px-6 py-4 pl-8">Patient</th>
                            <th class="px-6 py-4">Medicine</th>
                            <th class="px-6 py-4">Due Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Representative</th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white text-sm">
                        <tr v-for="order in combinedList" :key="order.id" class="group hover:bg-gray-50/50 transition-colors relative">
                            <!-- Patient -->
                            <td class="px-6 py-4 pl-8">
                                <div class="flex items-center">
                                     <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold bg-gray-100 text-gray-500 mr-3 ring-2 ring-white shadow-sm">
                                        {{ getInitials(order.patient?.name) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ order.patient?.name }}</p>
                                        <p class="text-xs text-gray-400 font-medium tracking-wide">{{ order.patient?.phone }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Medicine -->
                            <td class="px-6 py-4 text-gray-600 font-medium">
                                {{ order.medicine?.name }}
                            </td>
                            
                            <!-- Start Date -->
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800 block">{{ formatDate(order.expected_renewal_date) }}</span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold capitalize"
                                      :class="getStatusPillClass(order)">
                                    {{ order.renewal_status }}
                                </span>
                            </td>

                            <!-- Representative -->
                            <td class="px-6 py-4 text-gray-500">
                                <div class="flex items-center">
                                    <span>{{ order.representative?.user?.name }}</span>
                                    <button class="ml-2 text-gray-300 hover:text-emerald-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Menu Dot -->
                             <td class="px-4 py-4 text-right pr-6 action-menu">
                                <div class="relative">
                                    <button @click.stop="toggleMenu(order.id)" class="text-gray-300 hover:text-emerald-600 transition-colors p-1 rounded-md hover:bg-emerald-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                    </button>
                                    
                                    <!-- Dropdown -->
                                    <div v-if="openMenuId === order.id" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden transform origin-top-right transition-all">
                                        <div class="py-1">
                                            <a :href="`/admin/orders/${order.id}`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                                View Order Details
                                            </a>
                                            <a :href="`/admin/patients/${order.patient_id}`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                                Patient Profile
                                            </a>
                                            <div class="border-t border-gray-100 my-1"></div>
                                             <button @click="markAsDone(order.id)" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                                Complete Order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
             <div class="px-6 py-4 bg-white border-t border-gray-100 text-center">
                <a href="/admin/orders" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center justify-center gap-1 transition-colors">
                    View all renewals <span class="text-lg">→</span>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    stats: Object,
    upcomingRenewals: Array,
    overdueRenewals: Array,
    recentOrders: Array,
    ordersByCountry: Array,
});

// Local state for optimistic updates
const localOverdue = ref([]);
const localUpcoming = ref([]);
const openMenuId = ref(null);

// Initialize local state
onMounted(() => {
    localOverdue.value = [...props.overdueRenewals];
    localUpcoming.value = [...props.upcomingRenewals];
});

// Watch for prop changes to update local state (if data refreshes)
watch(() => props.overdueRenewals, (newVal) => localOverdue.value = [...newVal]);
watch(() => props.upcomingRenewals, (newVal) => localUpcoming.value = [...newVal]);

// Urgent Items Logic
const urgentItems = computed(() => {
    // Combine overdue and very soon upcoming
    const overdue = localOverdue.value.map(i => ({ ...i, isOverdue: true }));
    const urgentUpcoming = localUpcoming.value
        .filter(i => i.days_until_renewal <= 3)
        .map(i => ({ ...i, isOverdue: false }));
    return [...overdue, ...urgentUpcoming].slice(0, 3);
});

// Combined List for Table
const combinedList = computed(() => {
    return [...localOverdue.value, ...localUpcoming.value].slice(0, 5);
});

// Chart Colors
const chartColors = ['#0f766e', '#14b8a6', '#5eead4', '#ccfbf1', '#99f6e4'];
const topCountries = computed(() => props.ordersByCountry.slice(0, 4));

const topCode = computed(() => props.ordersByCountry[0]?.country || 'Unknown');
const topPercent = computed(() => {
    const total = props.stats.total_orders || 1; 
    const countryTotal = props.ordersByCountry[0]?.total || 0;
    return Math.round((countryTotal / total) * 100);
});

// Actions
const markAsDone = async (id) => {
    if (!confirm('Are you sure you want to mark this order as completed?')) return;

    // Optimistic remove
    const overdueIndex = localOverdue.value.findIndex(o => o.id === id);
    if (overdueIndex > -1) localOverdue.value.splice(overdueIndex, 1);
    
    const upcomingIndex = localUpcoming.value.findIndex(o => o.id === id);
    if (upcomingIndex > -1) localUpcoming.value.splice(upcomingIndex, 1);

    try {
        // Assuming typical Laravel Resource route: PUT /admin/orders/{id}
        // You might need to adjust the CSRF token handling if not global
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        await fetch(`/admin/orders/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: 'completed' })
        });
    } catch (error) {
        console.error('Failed to mark as done', error);
        alert('Failed to update order status. Please refresh.');
        // In a real app, revert optimistic update here
    }
};

const toggleMenu = (id) => {
    openMenuId.value = openMenuId.value === id ? null : id;
};

// Close menu when clicking outside
onMounted(() => {
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.action-menu')) {
            openMenuId.value = null;
        }
    });

    const ctx = document.getElementById('dashboardCountryChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: props.ordersByCountry.map(c => c.country),
                datasets: [{
                    data: props.ordersByCountry.map(c => c.total),
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                onClick: (evt, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const country = props.ordersByCountry[index].country;
                        // Redirect to orders filtered by country
                        window.location.href = `/admin/orders?search=${encodeURIComponent(country)}`;
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
});

// Helpers
const formatDate = (dateString) => {
    const options = { day: 'numeric', month: 'short', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
};

const getInitials = (name) => {
    if (!name) return '??';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
}

const getStatusPillClass = (order) => {
    if (order.days_until_renewal < 0) return 'bg-orange-100 text-orange-700';
    if (order.days_until_renewal <= 7) return 'bg-orange-100 text-orange-700';
    return 'bg-emerald-100 text-emerald-700';
};
</script>
