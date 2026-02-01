<template>
    <div class="space-y-8 p-4 md:p-8 font-sans bg-gray-50/50 min-h-screen">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Marketing Dashboard</h1>
                <p class="text-gray-500 mt-1 text-sm font-medium">Welcome back! Overview of your lead pipeline.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import
                </button>
                <a href="/marketing/leads/create" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl font-semibold text-sm hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg shadow-purple-500/30 flex items-center hover:-translate-y-0.5 transform duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Lead
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Total Pending -->
            <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-gray-100/60 overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                     <svg class="w-24 h-24 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 00-1-1H3zm6 4a1 1 0 100 2 1 1 0 000-2zm-2 4a1 1 0 100 2 1 1 0 000-2zm4 0a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 shadow-sm">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Pipeline</span>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Total Pending</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ stats.new_leads }}</h3>
                    <div class="flex items-center mt-2 text-xs font-medium text-emerald-600">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span>+12% vs last week</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Hot & Unassigned -->
            <div class="relative bg-gradient-to-br from-orange-500 to-amber-500 p-6 rounded-2xl shadow-lg shadow-orange-500/20 text-white overflow-hidden group hover:-translate-y-1 transition-transform">
                 <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors"></div>
                 <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/20">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    </div>
                   <div class="animate-pulse">
                        <span class="bg-white/20 border border-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">Urgent</span>
                   </div>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-orange-100 uppercase tracking-widest">Hot & Unassigned</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ stats.hot_leads_unassigned }}</h3>
                     <p class="text-xs text-orange-100 mt-2 flex items-center font-medium">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Needs immediate action
                    </p>
                </div>
            </div>

            <!-- Card 3: Assigned Today -->
            <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-gray-100/60 overflow-hidden group hover:shadow-md transition-shadow">
                 <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                    <svg class="w-24 h-24 text-cyan-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-600 shadow-sm">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="bg-cyan-100 text-cyan-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Today</span>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Assigned Today</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ stats.assigned_today }}</h3>
                    <div class="mt-2 text-xs flex items-center justify-between">
                         <span class="text-gray-500">Daily Target: <span class="font-bold text-gray-700">10</span></span>
                         <span class="text-cyan-600 font-bold">{{ Math.round((stats.assigned_today / 10) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full mt-1.5 overflow-hidden">
                        <div class="bg-cyan-500 h-full rounded-full transition-all duration-1000" :style="{ width: `${Math.min((stats.assigned_today / 10) * 100, 100)}%` }"></div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Monthly Goal -->
            <div class="relative bg-gray-900 p-6 rounded-2xl shadow-lg shadow-gray-500/20 text-white overflow-hidden group hover:-translate-y-1 transition-transform">
                 <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-purple-500/20 rounded-full blur-3xl"></div>
                 <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="text-2xl font-bold text-emerald-400">{{ score.percentage }}%</span>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Monthly Goal</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h3 class="text-3xl font-bold">{{ score.leads_assigned }}</h3>
                        <span class="text-gray-500 text-sm font-medium">/ {{ score.target_assigned }}</span>
                    </div>
                    <div class="w-full bg-gray-700 h-1.5 rounded-full mt-3 overflow-hidden">
                         <div class="bg-gradient-to-r from-emerald-400 to-teal-400 h-full rounded-full transition-all duration-1000" :style="{ width: `${Math.min(score.percentage, 100)}%` }"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ Math.max(0, score.target_assigned - score.leads_assigned) }} more to reach target</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Left: Charts & Tables -->
            <div class="xl:col-span-2 space-y-8">
                
                <!-- Assignment Velocity Chart -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100/60">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Assignment Velocity</h3>
                            <p class="text-sm text-gray-500">Leads assigned over the last 7 days</p>
                        </div>
                         <div class="flex items-center gap-4 text-xs font-semibold">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                                <span class="text-gray-600">Assigned</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-64 w-full">
                         <canvas id="velocityChart"></canvas>
                    </div>
                </div>

                <!-- Fresh Incoming Leads Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100/60 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                         <div>
                            <h3 class="text-lg font-bold text-gray-900">Recent Active Leads</h3>
                            <p class="text-sm text-gray-500">Items waiting to be processed</p>
                        </div>
                        <a href="/marketing/leads?status=new" class="text-sm font-bold text-purple-600 hover:text-purple-700 flex items-center">
                            View all
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="lead in incomingLeads" :key="lead.id" class="group hover:bg-purple-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-purple-200">
                                                {{ getInitials(lead.name) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-bold text-gray-900">{{ lead.name }}</div>
                                                <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                                     <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        {{ formatDate(lead.created_at) }}
                                                     </span>
                                                     <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                                     <span>{{ lead.source || 'Direct' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                         <span v-if="lead.lead_quality === 'hot'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                            🔥 Hot Lead
                                        </span>
                                        <span v-else-if="lead.lead_quality === 'warm'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            ☀️ Warm
                                        </span>
                                         <span v-else-if="lead.lead_quality === 'cold'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                            ❄️ Cold
                                        </span>
                                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                            👤 Patient
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a :href="`/marketing/leads/${lead.id}/edit`" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:text-purple-600 hover:border-purple-200 hover:bg-purple-50 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr v-if="incomingLeads.length === 0">
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="font-medium text-gray-900">All caught up!</p>
                                        <p class="text-sm">No pending leads to process.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar widgets -->
             <div class="space-y-8">
                
                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-purple-700 to-indigo-800 rounded-2xl p-6 text-white shadow-xl shadow-indigo-900/20 relative overflow-hidden group">
                     <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:bg-white/15 transition-colors"></div>
                    
                    <div class="flex items-center justify-between mb-6 relative z-10">
                        <h3 class="text-lg font-bold">Quick Actions</h3>
                        <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>

                    <div class="space-y-3 relative z-10">
                         <a href="/marketing/leads?quality=hot" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/5 hover:border-white/20 backdrop-blur-sm group/item">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20 mr-4 group-hover/item:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-sm">Call Hot Leads</p>
                                <p class="text-xs text-purple-200">{{ stats.hot_leads_unassigned }} pending</p>
                            </div>
                            <svg class="w-5 h-5 text-white/50 group-hover/item:text-white transition-colors transform group-hover/item:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>

                        <a href="/marketing/leads?status=new" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/5 hover:border-white/20 backdrop-blur-sm group/item">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20 mr-4 group-hover/item:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-sm">Process New Leads</p>
                                <p class="text-xs text-purple-200">{{ stats.new_leads }} waiting</p>
                            </div>
                            <svg class="w-5 h-5 text-white/50 group-hover/item:text-white transition-colors transform group-hover/item:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Lead Quality Chart -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100/60 flex flex-col items-center">
                    <div class="w-full flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900">Lead Quality</h3>
                        <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded">All Time</span>
                    </div>
                    <div class="w-full h-48 relative">
                        <canvas id="qualityChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-3xl font-bold text-gray-900">{{ totalCategorized }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Total</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 w-full mt-6">
                        <div class="text-center p-2 rounded-lg bg-orange-50">
                            <div class="text-orange-500 text-lg mb-1">🔥</div>
                            <div class="font-bold text-gray-900">{{ qualityData.hot }}</div>
                            <div class="text-[10px] text-gray-500 uppercase font-bold">Hot</div>
                        </div>
                         <div class="text-center p-2 rounded-lg bg-amber-50">
                            <div class="text-amber-500 text-lg mb-1">☀️</div>
                            <div class="font-bold text-gray-900">{{ qualityData.warm }}</div>
                            <div class="text-[10px] text-gray-500 uppercase font-bold">Warm</div>
                        </div>
                         <div class="text-center p-2 rounded-lg bg-gray-50">
                            <div class="text-gray-500 text-lg mb-1">❄️</div>
                            <div class="font-bold text-gray-900">{{ qualityData.cold }}</div>
                            <div class="text-[10px] text-gray-500 uppercase font-bold">Cold</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    stats: Object,
    incomingLeads: Array,
    recentActivities: Array,
    score: Object,
    velocityData: Array,
    qualityData: Object
});

const totalCategorized = computed(() => {
    return (props.qualityData.hot || 0) + (props.qualityData.warm || 0) + (props.qualityData.cold || 0);
});

const getInitials = (name) => {
    if (!name) return '??';
    return name.substring(0, 2).toUpperCase();
}

const formatDate = (dateString, rel = true) => {
    // Basic implementation for demo, replace with date-fns/moment if needed
    const date = new Date(dateString);
    if(rel) {
        // Simple relative time approximation
        const diff = new Date() - date;
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        if(days === 0) return 'Today';
        if(days === 1) return 'Yesterday';
        if(days < 7) return `${days}d ago`;
    }
    return date.toLocaleDateString();
}

onMounted(() => {
    // Velocity Chart
    const velocityCtx = document.getElementById('velocityChart');
    if (velocityCtx) {
        new Chart(velocityCtx, {
            type: 'line',
            data: {
                labels: props.velocityData.map(d => d.day),
                datasets: [
                    {
                        label: 'Assigned',
                        data: props.velocityData.map(d => d.count),
                        borderColor: '#8b5cf6', // purple-500
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(139, 92, 246, 0.2)');
                            gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#8b5cf6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    },
                     {
                        label: 'Target',
                        data: [5, 5, 5, 5, 5, 5, 5],
                        borderColor: '#38bdf8', // sky-400
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1f2937',
                        bodyColor: '#4b5563',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 10,
                        itemSort: (a, b) => b.raw - a.raw
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, color: '#f3f4f6' },
                        ticks: { stepSize: 1, font: { size: 10 } },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } },
                         border: { display: false }
                    }
                }
            }
        });
    }

    // Quality Chart
    const qualityCtx = document.getElementById('qualityChart');
    if (qualityCtx) {
        new Chart(qualityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hot', 'Warm', 'Cold'],
                datasets: [{
                    data: [props.qualityData.hot, props.qualityData.warm, props.qualityData.cold],
                    backgroundColor: ['#f97316', '#f59e0b', '#cbd5e1'], // Orange, Amber, Slate-300
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                         backgroundColor: '#1f2937',
                         padding: 12,
                         cornerRadius: 8
                    }
                }
            }
        });
    }
});
</script>
