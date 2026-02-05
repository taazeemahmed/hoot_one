@props(['mobile' => false])

<div class="flex flex-col w-64 h-screen px-4 py-6 overflow-y-auto bg-white border-r border-gray-100">
    <div class="flex items-center justify-between mb-8 px-2">
        <a href="#" class="flex items-center">
            <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center mr-2">
                <span class="text-white font-bold text-sm">H</span>
            </div>
            <span class="text-xl font-bold text-slate-900">HootoneOne</span>
        </a>
        @if($mobile)
        <button @click="sidebarOpen = false" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @endif
    </div>

    <div class="flex flex-col justify-between flex-1 mt-2">
        <nav @click="if (window.innerWidth < 1024) sidebarOpen = false">
            @php
                $role = auth()->user()->role;
            @endphp

            @if($role === 'super_admin')
            <div class="space-y-1">
                <label class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</label>
                
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.leads.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.leads.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Leads</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.patients.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.patients.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Patients</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.orders.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Orders</span>
                </a>
            </div>

            <div class="space-y-1 mt-6 pt-6 border-t border-gray-100">
                <label class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Team</label>
                
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.representatives.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.representatives.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Representatives</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.marketing-members.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.marketing-members.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Marketing Team</span>
                </a>
            </div>

            <div class="space-y-1 mt-6 pt-6 border-t border-gray-100">
                <label class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Catalog</label>
                
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.medicines.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.medicines.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Medicines</span>
                </a>
            </div>
            
            <div class="space-y-1 mt-6 pt-6 border-t border-gray-100">
                <label class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</label>
                
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.analytics.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.analytics.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Analytics</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.whatsapp_logs.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.whatsapp_logs.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">WhatsApp Logs</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('admin.settings.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Settings</span>
                </a>
            </div>

            @elseif($role === 'representative')
            <div class="space-y-1">
                <label class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Portal</label>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('representative.dashboard') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('representative.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('representative.leads.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('representative.leads.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">My Leads</span>
                    @php
                        $pendingLeadsCount = \App\Models\Patient::where('representative_id', auth()->user()->representative?->id)
                            ->whereIn('lead_status', ['assigned', 'contacted', 'negotiating'])
                            ->count();
                    @endphp
                    @if($pendingLeadsCount > 0)
                    <span class="ml-auto bg-orange-100 text-orange-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingLeadsCount }}</span>
                    @endif
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('representative.patients.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('representative.patients.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">My Patients</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('representative.orders.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('representative.orders.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Orders</span>
                </a>
            </div>

            @elseif($role === 'marketing_member')
            <div class="space-y-1">
                <label class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketing</label>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('marketing.dashboard') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('marketing.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('marketing.leads.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-200 rounded-xl group" 
                   href="{{ route('marketing.leads.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">My Leads</span>
                </a>
            </div>
            @endif
        </nav>

        <div class="mt-6 pt-6 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2.5 text-gray-600 transition-all duration-200 rounded-xl hover:bg-red-50 hover:text-red-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="mx-3 font-medium text-sm">Logout</span>
                </button>
            </form>
            
            <div class="flex items-center px-4 py-3 mt-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-x-3">
                    <div class="w-10 h-10 overflow-hidden border-2 border-white rounded-full shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=1e293b&background=f1f5f9" class="object-cover w-full h-full" alt="avatar">
                    </div>
                    
                    <div class="text-left">
                        <h1 class="text-sm font-semibold text-gray-900 capitalize">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
