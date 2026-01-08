<div class="flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto border-r rtl:border-r-0 rtl:border-l bg-white border-gray-200">
    <a href="#" class="flex items-center mx-auto mb-6">
        <span class="text-2xl font-bold text-hoot-dark">Hootone One</span>
    </a>

    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav>
            @php
                $role = auth()->user()->role;
                $prefix = $role === 'super_admin' ? 'admin.' : 'representative.';
            @endphp

            @if($role === 'super_admin')
            <div class="space-y-2">
                <label class="px-3 text-xs text-gray-500 uppercase">Menu</label>
                
                <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="mx-4 font-medium">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.representatives.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.representatives.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="mx-4 font-medium">Representatives</span>
                </a>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.patients.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.patients.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="mx-4 font-medium">Patients</span>
                </a>

                 <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.medicines.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.medicines.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span class="mx-4 font-medium">Medicines</span>
                </a>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.orders.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="mx-4 font-medium">Orders</span>
                </a>
            </div>
            
             <div class="space-y-2 mt-4 border-t pt-2">
                <label class="px-3 text-xs text-gray-500 uppercase">Configuration</label>
                
                 <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.whatsapp_logs.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.whatsapp_logs.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="mx-4 font-medium">WhatsApp Logs</span>
                </a>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('admin.settings.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('admin.settings.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="mx-4 font-medium">Settings</span>
                </a>
             </div>
            @else
            <div class="space-y-2">
                <label class="px-3 text-xs text-gray-500 uppercase">Representative</label>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('representative.dashboard') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('representative.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="mx-4 font-medium">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('representative.patients.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('representative.patients.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="mx-4 font-medium">My Patients</span>
                </a>

                <a class="flex items-center px-4 py-3 {{ request()->routeIs('representative.orders.*') ? 'bg-hoot-light text-hoot-dark' : 'text-gray-600 hover:bg-gray-50 hover:text-hoot-dark' }} transition-colors duration-300 transform rounded-lg" 
                   href="{{ route('representative.orders.index') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="mx-4 font-medium">Orders</span>
                </a>

            </div>
            @endif
        </nav>

        <div class="mt-6">
            <div class="flex items-center justify-between mt-4">
                 <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-600 transition-colors duration-300 transform rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="mx-4 font-medium">Logout</span>
                    </button>
                </form>
            </div>
            
            <a href="#" class="flex items-center px-4 py-2 mt-4 text-gray-600 transition-colors duration-300 transform rounded-lg hover:bg-gray-100 hover:text-gray-700">
                <div class="flex items-center gap-x-2">
                    <div class="w-8 h-8 overflow-hidden border-2 border-gray-400 rounded-full">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=1B4332&background=F0FDF4" class="object-cover w-full h-full" alt="avatar">
                    </div>
                    
                    <div class="text-left">
                        <h1 class="text-xs font-semibold text-gray-700 capitalize">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="text-[10px] text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
