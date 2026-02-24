<template>
    <div class="space-y-4 sm:space-y-6">
        <!-- HERO GREETING SECTION -->
        <div class="relative overflow-hidden bg-gradient-to-br from-corp-900 via-corp-800 to-hoot-dark rounded-2xl p-5 sm:p-6 text-white">
            <!-- Decorative background pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="absolute -right-8 -top-8 w-48 h-48 text-white" viewBox="0 0 100 100" fill="currentColor">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="2" opacity="0.3"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="2" opacity="0.5"/>
                    <circle cx="50" cy="50" r="10"/>
                </svg>
                <svg class="absolute -left-4 -bottom-4 w-32 h-32 text-white" viewBox="0 0 100 100" fill="currentColor">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="2" opacity="0.3"/>
                </svg>
            </div>

            <div class="relative">
                <!-- Time-based greeting -->
                <div class="flex items-center gap-2 mb-2">
                    <svg v-if="greetingIcon === 'sun'" class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg v-else-if="greetingIcon === 'cloud'" class="w-5 h-5 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    <svg v-else class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <span class="text-white/80 text-sm font-medium">{{ greeting }}</span>
                </div>

                <!-- Name and Title -->
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ greetingLine }}</h1>
                <p class="text-white/70 text-sm mb-4">{{ formatDateFull(new Date()) }}</p>

                <!-- Motivational message based on workload -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 flex items-start gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg v-if="urgentCount === 0" class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg v-else-if="urgentCount <= 3" class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <svg v-else-if="urgentCount <= 10" class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <svg v-else class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white">{{ statusMessage }}</p>
                        <p class="text-white/70 text-sm mt-0.5">{{ statusSubtext }}</p>
                    </div>
                </div>
            </div>

            <!-- Tutorial trigger integrated -->
            <Transition name="fade">
                <button v-if="showTutorialHint && !tutorialDismissed"
                        @click="startTutorial"
                        class="absolute top-3 right-3 text-white/70 hover:text-white text-xs bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-full transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Quick Guide
                </button>
            </Transition>
        </div>

        <!-- Daily Focus Header -->
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-corp-100 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h2 class="text-lg font-semibold text-corp-900">Today's Focus</h2>
                    <p class="text-sm text-corp-400 mt-0.5">What needs your attention</p>
                </div>
                <button @click="showTutorial = true" class="p-2 text-corp-300 hover:text-hoot-green hover:bg-corp-50 rounded-lg transition-colors" title="Help">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Daily Summary Pills -->
            <div class="flex flex-wrap gap-2">
                <span v-if="pendingLeads.length > 0" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-full text-sm font-medium border border-amber-200">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    {{ pendingLeads.length }} leads need follow-up
                </span>
                <span v-if="overdueRenewals.length > 0" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 rounded-full text-sm font-medium border border-red-200">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    {{ overdueRenewals.length }} overdue renewals
                </span>
                <span v-if="upcomingRenewals.length > 0" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-corp-50 text-corp-700 rounded-full text-sm font-medium border border-corp-200">
                    <span class="w-2 h-2 bg-corp-500 rounded-full"></span>
                    {{ upcomingRenewals.length }} renewals due soon
                </span>
                <span v-if="stats.active_orders > 0" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-700 rounded-full text-sm font-medium border border-orange-200">
                    <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                    {{ stats.active_orders }} active orders
                </span>
                <span v-if="pendingLeads.length === 0 && overdueRenewals.length === 0 && upcomingRenewals.length === 0" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-700 rounded-full text-sm font-medium border border-orange-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    All caught up!
                </span>
            </div>
        </div>

        <!-- Next Best Actions (THE HEART) -->
        <div class="bg-white rounded-xl border border-corp-100 overflow-hidden shadow-sm">
            <div class="px-4 py-3 border-b border-corp-100 flex items-center justify-between bg-corp-50">
                <h2 class="font-semibold text-corp-900">Next Actions</h2>
                <span class="text-xs text-corp-400">Most important first</span>
            </div>

            <div class="divide-y divide-corp-50">
                <!-- Overdue Renewals (Highest Priority) -->
                <div v-for="order in overdueRenewals.slice(0, 2)" :key="'overdue-'+order.id"
                     class="p-4 hover:bg-corp-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-200">OVERDUE</span>
                            </div>
                            <p class="font-medium text-corp-900 truncate">{{ order.patient.name }}</p>
                            <p class="text-sm text-corp-400">Renewal was due {{ timeAgo(order.expected_renewal_date) }}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a :href="'https://wa.me/' + cleanPhone(order.patient.phone)" target="_blank"
                               class="w-10 h-10 bg-emerald-500 hover:bg-emerald-600 rounded-full flex items-center justify-center text-white transition-colors shadow-sm" title="WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </a>
                            <a :href="'tel:' + order.patient.phone"
                               class="w-10 h-10 bg-corp-600 hover:bg-corp-700 rounded-full flex items-center justify-center text-white transition-colors shadow-sm" title="Call">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hot Leads Without Recent Activity -->
                <div v-for="lead in hotLeadsNeedingAttention.slice(0, 3)" :key="'lead-'+lead.id"
                     class="p-4 hover:bg-corp-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                             :class="(lead.lead_quality || 'warm') === 'hot' ? 'bg-orange-100' : 'bg-amber-100'">
                            <svg v-if="(lead.lead_quality || 'warm') === 'hot'" class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                            <svg v-else class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded border uppercase"
                                      :class="(lead.lead_quality || 'warm') === 'hot' ? 'text-orange-700 bg-orange-50 border-orange-200' : 'text-amber-700 bg-amber-50 border-amber-200'">
                                    {{ lead.lead_quality || 'warm' }}
                                </span>
                                <span v-if="!lead.latest_activity" class="text-xs text-corp-300">New lead</span>
                                <span v-else class="text-xs text-corp-300">Last contact {{ timeAgo(lead.latest_activity.created_at) }}</span>
                            </div>
                            <p class="font-medium text-corp-900 truncate">{{ lead.name || lead.phone }}</p>
                            <p class="text-sm text-corp-400">{{ lead.country }}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a :href="'https://wa.me/' + cleanPhone(lead.phone)" target="_blank"
                               class="w-10 h-10 bg-emerald-500 hover:bg-emerald-600 rounded-full flex items-center justify-center text-white transition-colors shadow-sm" title="WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </a>
                            <a :href="'tel:' + lead.phone"
                               class="w-10 h-10 bg-corp-600 hover:bg-corp-700 rounded-full flex items-center justify-center text-white transition-colors shadow-sm" title="Call">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Renewals -->
                <div v-for="order in upcomingRenewals.slice(0, 2)" :key="'renewal-'+order.id"
                     class="p-4 hover:bg-corp-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-corp-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-corp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-corp-600 bg-corp-50 px-2 py-0.5 rounded border border-corp-200">RENEWAL</span>
                            </div>
                            <p class="font-medium text-corp-900 truncate">{{ order.patient.name }}</p>
                            <p class="text-sm text-corp-400">Due {{ formatDate(order.expected_renewal_date) }} · {{ order.medicine.name }}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a :href="'https://wa.me/' + cleanPhone(order.patient.phone)" target="_blank"
                               class="w-10 h-10 bg-emerald-500 hover:bg-emerald-600 rounded-full flex items-center justify-center text-white transition-colors shadow-sm" title="WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </a>
                            <a :href="'tel:' + order.patient.phone"
                               class="w-10 h-10 bg-corp-600 hover:bg-corp-700 rounded-full flex items-center justify-center text-white transition-colors shadow-sm" title="Call">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="overdueRenewals.length === 0 && hotLeadsNeedingAttention.length === 0 && upcomingRenewals.length === 0"
                     class="p-8 text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-corp-900">No urgent actions</p>
                    <p class="text-sm text-corp-400 mt-1">You're all caught up for now</p>
                </div>
            </div>

            <!-- View All Link -->
            <div v-if="pendingLeads.length > 0" class="px-4 py-3 bg-corp-50 border-t border-corp-100">
                <a href="/portal/leads" class="text-sm font-medium text-hoot-green hover:text-hoot-dark flex items-center justify-center gap-1">
                    View all {{ pendingLeads.length }} leads
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Action Summary Cards (Tappable) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="/portal/leads" class="bg-white rounded-xl p-4 border border-corp-100 hover:border-hoot-green hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-corp-900">{{ pendingLeads.length }}</p>
                <p class="text-xs text-corp-400 font-medium">Pending Leads</p>
            </a>

            <a href="/portal/patients" class="bg-white rounded-xl p-4 border border-corp-100 hover:border-hoot-green hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-corp-100 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-corp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-corp-900">{{ stats.total_patients }}</p>
                <p class="text-xs text-corp-400 font-medium">My Patients</p>
            </a>

            <a href="/portal/orders" class="bg-white rounded-xl p-4 border border-corp-100 hover:border-hoot-green hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-hoot-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-corp-900">{{ stats.active_orders }}</p>
                <p class="text-xs text-corp-400 font-medium">Active Orders</p>
            </a>

            <div class="bg-white rounded-xl p-4 border border-corp-100 hover:border-hoot-green hover:shadow-md transition-all group cursor-default">
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-corp-900">{{ upcomingRenewals.length }}</p>
                <p class="text-xs text-corp-400 font-medium">Renewals Due</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-4 border border-corp-100 shadow-sm">
            <h3 class="font-semibold text-corp-700 mb-3 text-sm">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <a href="/portal/orders/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-corp-900 text-white rounded-lg text-sm font-medium hover:bg-hoot-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Order
                </a>
                <a href="/portal/patients/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-corp-50 text-corp-700 border border-corp-200 rounded-lg text-sm font-medium hover:bg-corp-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Add Patient
                </a>
                <a href="/portal/leads" class="inline-flex items-center gap-2 px-4 py-2.5 bg-corp-50 text-corp-700 border border-corp-200 rounded-lg text-sm font-medium hover:bg-corp-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Log Activity
                </a>
            </div>
        </div>

        <!-- Tutorial Modal -->
        <Transition name="modal">
            <div v-if="showTutorial" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/50" @click="showTutorial = false"></div>
                <div class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full max-w-md max-h-[85vh] overflow-hidden">
                    <!-- Tutorial Content -->
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900">{{ tutorialSteps[tutorialStep].title }}</h3>
                            <button @click="showTutorial = false" class="text-gray-400 hover:text-gray-600 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Tutorial Illustration -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-4 flex items-center justify-center min-h-[160px]">
                            <div v-html="tutorialSteps[tutorialStep].icon" class="text-hoot-green"></div>
                        </div>

                        <p class="text-gray-600 text-sm mb-6">{{ tutorialSteps[tutorialStep].description }}</p>

                        <!-- Progress Dots -->
                        <div class="flex justify-center gap-2 mb-4">
                            <span v-for="(_, index) in tutorialSteps" :key="index"
                                  :class="index === tutorialStep ? 'bg-hoot-green' : 'bg-gray-200'"
                                  class="w-2 h-2 rounded-full transition-colors"></span>
                        </div>

                        <!-- Navigation -->
                        <div class="flex gap-3">
                            <button v-if="tutorialStep > 0" @click="tutorialStep--"
                                    class="flex-1 py-2.5 text-gray-600 bg-gray-100 rounded-lg font-medium text-sm hover:bg-gray-200 transition-colors">
                                Back
                            </button>
                            <button v-if="tutorialStep < tutorialSteps.length - 1" @click="tutorialStep++"
                                    class="flex-1 py-2.5 text-white bg-hoot-dark rounded-lg font-medium text-sm hover:bg-hoot-green transition-colors">
                                Next
                            </button>
                            <button v-else @click="completeTutorial"
                                    class="flex-1 py-2.5 text-white bg-hoot-dark rounded-lg font-medium text-sm hover:bg-hoot-green transition-colors">
                                Got it!
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
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
    data() {
        return {
            showTutorial: false,
            tutorialStep: 0,
            tutorialDismissed: localStorage.getItem('rep_tutorial_dismissed') === 'true',
            showTutorialHint: true,
            tutorialSteps: [
                {
                    title: 'Welcome to your Dashboard',
                    description: 'This is your daily action center. It shows you exactly what needs your attention today — leads to follow up, renewals due, and patients to contact.',
                    icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'
                },
                {
                    title: 'Contact Leads Quickly',
                    description: 'Tap the green WhatsApp button or blue call button to contact leads instantly. No need to copy numbers — just tap and connect.',
                    icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>'
                },
                {
                    title: 'My Leads Page',
                    description: 'Go to "My Leads" to see all your assigned leads. You can log activities, update status, and convert leads to patients.',
                    icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
                },
                {
                    title: 'Log Every Interaction',
                    description: 'After each call or message, log the activity. This helps you remember what happened and when to follow up next.',
                    icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'
                },
                {
                    title: 'You\'re Ready!',
                    description: 'That\'s all you need to know. Start by contacting your top leads shown on this dashboard. You can always tap the help icon to see this guide again.',
                    icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                }
            ]
        };
    },
    computed: {
        representativeName() {
            return this.representative?.user?.name || this.representative?.name || 'there';
        },
        greetingLine() {
            return `${this.greeting} ${this.representativeName}`;
        },
        greeting() {
            const hour = new Date().getHours();
            if (hour < 12) return 'Good morning';
            if (hour < 17) return 'Good afternoon';
            return 'Good evening';
        },
        greetingIcon() {
            const hour = new Date().getHours();
            if (hour < 12) return 'sun';
            if (hour < 17) return 'cloud';
            return 'moon';
        },
        urgentCount() {
            return this.overdueRenewals.length + this.hotLeadsNeedingAttention.length;
        },
        statusMessage() {
            const overdueCount = this.overdueRenewals.length;
            const leadsCount = this.pendingLeads.length;
            const hotLeads = this.hotLeadsNeedingAttention.length;

            if (overdueCount === 0 && leadsCount === 0) {
                return "You're all caught up!";
            }
            if (overdueCount > 5) {
                return `${overdueCount} renewals need attention`;
            }
            if (hotLeads > 0) {
                return `${hotLeads} hot leads waiting`;
            }
            if (leadsCount > 0) {
                return `${leadsCount} leads to follow up`;
            }
            return "Ready for a productive day";
        },
        statusSubtext() {
            const overdueCount = this.overdueRenewals.length;
            const leadsCount = this.pendingLeads.length;

            if (overdueCount === 0 && leadsCount === 0) {
                return "Great job keeping up with your patients";
            }
            if (overdueCount > 5) {
                return "Start with the oldest ones first";
            }
            if (leadsCount > 3) {
                return "Contact them today for best results";
            }
            return "Your daily actions are listed below";
        },
        hotLeadsNeedingAttention() {
            // Sort leads by: hot first, then no activity, then oldest activity
            return [...this.pendingLeads].sort((a, b) => {
                // Hot leads first
                const qualityOrder = { hot: 0, warm: 1, cold: 2 };
                const aQuality = qualityOrder[a.lead_quality] ?? 1;
                const bQuality = qualityOrder[b.lead_quality] ?? 1;
                if (aQuality !== bQuality) return aQuality - bQuality;

                // No activity first
                if (!a.latest_activity && b.latest_activity) return -1;
                if (a.latest_activity && !b.latest_activity) return 1;

                // Then by oldest activity
                if (a.latest_activity && b.latest_activity) {
                    return new Date(a.latest_activity.created_at) - new Date(b.latest_activity.created_at);
                }

                return 0;
            });
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return 'N/A';
            return dayjs(date).format('D MMM');
        },
        formatDateFull(date) {
            if (!date) return '';
            return dayjs(date).format('dddd, D MMMM YYYY');
        },
        timeAgo(date) {
            if (!date) return 'never';
            return dayjs(date).fromNow();
        },
        cleanPhone(phone) {
            if (!phone) return '';
            return phone.replace(/[^0-9+]/g, '');
        },
        startTutorial() {
            this.tutorialStep = 0;
            this.showTutorial = true;
        },
        dismissTutorial() {
            this.showTutorialHint = false;
            localStorage.setItem('rep_tutorial_dismissed', 'true');
            this.tutorialDismissed = true;
        },
        completeTutorial() {
            this.showTutorial = false;
            this.dismissTutorial();
        }
    },
    mounted() {
        // Show tutorial on first visit
        if (!this.tutorialDismissed && !localStorage.getItem('rep_dashboard_visited')) {
            setTimeout(() => {
                this.showTutorial = true;
                localStorage.setItem('rep_dashboard_visited', 'true');
            }, 1000);
        }
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.modal-enter-active, .modal-leave-active {
    transition: all 0.3s ease;
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
.modal-enter-from .relative, .modal-leave-to .relative {
    transform: translateY(100%);
}
</style>
