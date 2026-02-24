<x-app-layout>
    <div x-data="leadsPage()" class="space-y-4">
        <!-- Tutorial Hint -->
        <template x-if="showTutorialHint && !localStorage.getItem('leads_tutorial_done')">
            <div class="bg-hoot-light border border-hoot-green/20 rounded-xl p-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-hoot-green/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-hoot-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-hoot-dark">First time? <button @click="startTutorial()" class="font-semibold underline">Learn how to manage leads</button></span>
                </div>
                <button @click="dismissTutorialHint()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Leads</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $leads->total() }} lead{{ $leads->total() !== 1 ? 's' : '' }} to follow up</p>
            </div>
            <button @click="startTutorial()" class="p-2 text-gray-400 hover:text-hoot-green hover:bg-hoot-light rounded-lg transition-colors" title="Help">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('representative.leads.index') }}" class="bg-white rounded-xl border border-gray-100 p-3">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green"
                        placeholder="Search leads by name, phone, or country..."
                    />
                </div>
                <button type="submit" class="px-4 py-2.5 bg-hoot-dark hover:bg-hoot-green text-white rounded-xl text-sm font-medium transition-colors">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('representative.leads.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        @if(session('success'))
            <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Lead Cards -->
        <div class="space-y-3">
            @forelse($leads as $lead)
                @php
                    $qualityColors = [
                        'hot' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'warm' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'cold' => 'bg-corp-50 text-corp-600 border-corp-200',
                    ];
                    $quality = $lead->lead_quality ?? 'warm';
                    $hasNoActivity = !$lead->latestActivity;
                    $isUrgent = $quality === 'hot' || $hasNoActivity;
                @endphp

                <div class="bg-white rounded-xl border {{ $isUrgent ? 'border-amber-200 shadow-sm' : 'border-corp-100' }} overflow-hidden">

                    <!-- Card Content -->
                    <div class="p-4">
                        <!-- Top Row: Name + Quality Badge -->
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-corp-900 truncate">{{ $lead->name ?: $lead->phone }}</h3>
                                <p class="text-sm text-corp-400 flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $lead->country }}
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $qualityColors[$quality] ?? $qualityColors['warm'] }}">
                                @if($quality === 'hot')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                @elseif($quality === 'warm')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                @endif
                                {{ ucfirst($quality) }}
                            </span>
                        </div>

                        <!-- Last Activity Info -->
                        <div class="text-sm text-corp-400 mb-3">
                            @if($lead->latestActivity)
                                <span class="text-corp-500">Last contact:</span>
                                {{ $lead->latestActivity->created_at->diffForHumans() }}
                                <span class="text-corp-200">·</span>
                                <span class="text-corp-500">{{ ucfirst($lead->latestActivity->type) }}</span>
                            @else
                                <span class="text-amber-600 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    New lead - not contacted yet
                                </span>
                            @endif
                        </div>

                        <!-- Phone copy row -->
                        <div class="flex items-center gap-2 mb-3 bg-corp-50 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-corp-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-sm font-mono text-corp-700 flex-1">{{ $lead->phone }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ $lead->phone }}').then(()=>{this.innerHTML='<svg class=\'w-4 h-4 text-orange-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>';setTimeout(()=>{this.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'/></svg>'},1500)})"
                                    class="p-1.5 text-corp-400 hover:text-corp-700 hover:bg-corp-100 rounded-md transition-colors" title="Copy phone">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <!-- WhatsApp (Primary) -->
                            <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $lead->phone) }}"
                               target="_blank"
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                WhatsApp
                            </a>

                            <!-- Call -->
                            <a href="tel:{{ $lead->phone }}"
                               class="w-11 h-11 inline-flex items-center justify-center bg-corp-600 hover:bg-corp-700 text-white rounded-lg transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </a>

                            <!-- Log Activity (Primary Action) -->
                            <button @click="openLogModal({{ $lead->id }}, '{{ addslashes($lead->name ?: $lead->phone) }}', '{{ $lead->lead_quality ?? 'warm' }}')"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-corp-900 hover:bg-corp-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Log Activity
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-hoot-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">All caught up!</h3>
                    <p class="text-sm text-gray-500">No pending leads assigned to you right now.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="bg-white rounded-xl border border-gray-100 px-4 py-3">
                {{ $leads->links() }}
            </div>
        @endif

        <!-- Log Activity Bottom Sheet Modal -->
        <div x-show="showLogModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50"
             style="display: none;">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50" @click="closeLogModal()"></div>

            <!-- Bottom Sheet -->
            <div x-show="showLogModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl max-h-[90vh] overflow-y-auto safe-bottom">

                <!-- Handle -->
                <div class="sticky top-0 bg-white pt-3 pb-2 px-4 border-b border-gray-100">
                    <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto mb-3"></div>
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">Log Activity</h3>
                        <button @click="closeLogModal()" class="p-1 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1" x-text="'For ' + currentLeadName"></p>
                </div>

                <form :action="'/portal/leads/' + currentLeadId + '/activity'" method="POST" class="p-4 space-y-4">
                    @csrf

                    <!-- Activity Type - Quick Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">What did you do?</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative">
                                <input type="radio" name="type" value="call" class="peer sr-only" checked>
                                <div class="p-3 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-hoot-green peer-checked:bg-hoot-light transition-all">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-gray-600 peer-checked:text-hoot-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Call</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="type" value="whatsapp" class="peer sr-only">
                                <div class="p-3 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-hoot-green peer-checked:bg-hoot-light transition-all">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    </svg>
                                    <span class="text-xs font-medium">WhatsApp</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="type" value="note" class="peer sr-only">
                                <div class="p-3 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-hoot-green peer-checked:bg-hoot-light transition-all">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Note</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Outcome - Smart Suggestions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">What happened?</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <button type="button" @click="result = 'Interested'"
                                    :class="result === 'Interested' ? 'bg-hoot-green text-white border-hoot-green' : 'bg-white text-gray-700 border-gray-200'"
                                    class="px-3 py-1.5 border rounded-full text-sm font-medium transition-colors">
                                Interested
                            </button>
                            <button type="button" @click="result = 'No Answer'"
                                    :class="result === 'No Answer' ? 'bg-hoot-green text-white border-hoot-green' : 'bg-white text-gray-700 border-gray-200'"
                                    class="px-3 py-1.5 border rounded-full text-sm font-medium transition-colors">
                                No Answer
                            </button>
                            <button type="button" @click="result = 'Call Later'"
                                    :class="result === 'Call Later' ? 'bg-hoot-green text-white border-hoot-green' : 'bg-white text-gray-700 border-gray-200'"
                                    class="px-3 py-1.5 border rounded-full text-sm font-medium transition-colors">
                                Call Later
                            </button>
                            <button type="button" @click="result = 'Not Interested'"
                                    :class="result === 'Not Interested' ? 'bg-hoot-green text-white border-hoot-green' : 'bg-white text-gray-700 border-gray-200'"
                                    class="px-3 py-1.5 border rounded-full text-sm font-medium transition-colors">
                                Not Interested
                            </button>
                        </div>
                        <input type="text" name="result" x-model="result"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green"
                               placeholder="Or type your own..." required>
                    </div>

                    <!-- Lead Temperature Update -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lead temperature</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative">
                                <input type="radio" name="lead_quality" value="hot" class="peer sr-only" :checked="currentLeadQuality === 'hot'">
                                <div class="p-2 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-orange-400 peer-checked:bg-orange-50 transition-all">
                                    <svg class="w-5 h-5 mx-auto text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                    <span class="block text-xs font-medium text-gray-600 mt-1">Hot</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lead_quality" value="warm" class="peer sr-only" :checked="currentLeadQuality === 'warm'">
                                <div class="p-2 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-amber-400 peer-checked:bg-amber-50 transition-all">
                                    <svg class="w-5 h-5 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span class="block text-xs font-medium text-gray-600 mt-1">Warm</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lead_quality" value="cold" class="peer sr-only" :checked="currentLeadQuality === 'cold'">
                                <div class="p-2 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-400 peer-checked:bg-blue-50 transition-all">
                                    <svg class="w-5 h-5 mx-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                    <span class="block text-xs font-medium text-gray-600 mt-1">Cold</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Optional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea name="notes" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green resize-none"
                                  placeholder="Any additional details..."></textarea>
                    </div>

                    <!-- Status Update -->
                    <div class="border-t border-gray-100 pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update status</label>
                        <select name="status_update" x-model="statusUpdate"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green bg-white">
                            <option value="">Keep current status</option>
                            <option value="contacted">Mark as Contacted</option>
                            <option value="negotiating">Negotiating</option>
                            <option value="converted">Convert to Patient</option>
                            <option value="lost">Mark as Lost</option>
                        </select>
                    </div>

                    <!-- Conversion Fields (Shown when converting) -->
                    <div x-show="statusUpdate === 'converted'" x-transition class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                        <h4 class="font-medium text-orange-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Create first order
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Medicine</label>
                                <select name="medicine_id" class="w-full px-3 py-2 border border-orange-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-200 focus:border-orange-400 bg-white">
                                    <option value="">Select medicine...</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Packs ordered</label>
                                <input type="number" name="packs_ordered" value="1" min="1"
                                       class="w-full px-3 py-2 border border-orange-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-200 focus:border-orange-400">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="sticky bottom-0 bg-white pt-2 pb-4 -mx-4 px-4 border-t border-gray-100 mt-4">
                        <button type="submit"
                                :disabled="isSubmitting"
                                class="w-full py-3 bg-hoot-dark hover:bg-hoot-green text-white rounded-xl font-medium text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg x-show="isSubmitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isSubmitting ? 'Saving...' : 'Save Activity'"></span>
                        </button>
                        <p class="text-xs text-center text-gray-400 mt-2">You can update this later</p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tutorial Modal -->
        <div x-show="showTutorial"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
             style="display: none;">

            <div class="fixed inset-0 bg-black/50" @click="showTutorial = false"></div>

            <div x-show="showTutorial"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 sm:scale-100"
                 x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full max-w-md overflow-hidden">

                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900" x-text="tutorialSteps[tutorialStep].title"></h3>
                        <button @click="showTutorial = false" class="text-gray-400 hover:text-gray-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Tutorial Icon -->
                    <div class="bg-hoot-light rounded-xl p-6 mb-4 flex items-center justify-center">
                        <div class="text-hoot-green" x-html="tutorialSteps[tutorialStep].icon"></div>
                    </div>

                    <p class="text-gray-600 text-sm mb-6" x-text="tutorialSteps[tutorialStep].description"></p>

                    <!-- Progress Dots -->
                    <div class="flex justify-center gap-2 mb-4">
                        <template x-for="(step, index) in tutorialSteps" :key="index">
                            <span :class="index === tutorialStep ? 'bg-hoot-green' : 'bg-gray-200'" class="w-2 h-2 rounded-full transition-colors"></span>
                        </template>
                    </div>

                    <!-- Navigation -->
                    <div class="flex gap-3">
                        <button x-show="tutorialStep > 0" @click="tutorialStep--"
                                class="flex-1 py-2.5 text-gray-600 bg-gray-100 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">
                            Back
                        </button>
                        <button x-show="tutorialStep < tutorialSteps.length - 1" @click="tutorialStep++"
                                class="flex-1 py-2.5 text-white bg-hoot-dark rounded-xl font-medium text-sm hover:bg-hoot-green transition-colors">
                            Next
                        </button>
                        <button x-show="tutorialStep === tutorialSteps.length - 1" @click="completeTutorial()"
                                class="flex-1 py-2.5 text-white bg-hoot-dark rounded-xl font-medium text-sm hover:bg-hoot-green transition-colors">
                            Got it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function leadsPage() {
            return {
                showLogModal: false,
                showTutorial: false,
                showTutorialHint: true,
                currentLeadId: null,
                currentLeadName: '',
                currentLeadQuality: 'warm',
                result: '',
                statusUpdate: '',
                isSubmitting: false,
                tutorialStep: 0,
                tutorialSteps: [
                    {
                        title: 'Your Lead List',
                        description: 'This page shows all leads assigned to you. Hot leads and those without recent activity appear first - they need your attention most.',
                        icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
                    },
                    {
                        title: 'Contact with One Tap',
                        description: 'Tap the green WhatsApp button to message instantly, or the blue button to call. No need to copy numbers - just tap and connect.',
                        icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>'
                    },
                    {
                        title: 'Log Every Activity',
                        description: 'After contacting a lead, tap "Log Activity" to record what happened. Choose from quick options or type your own. This helps you track progress.',
                        icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'
                    },
                    {
                        title: 'Convert to Patient',
                        description: 'When a lead is ready to buy, select "Convert to Patient" in the status dropdown. Add their first order details and they will move to your patients list.',
                        icon: '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    }
                ],

                openLogModal(leadId, leadName, leadQuality) {
                    this.currentLeadId = leadId;
                    this.currentLeadName = leadName;
                    this.currentLeadQuality = leadQuality || 'warm';
                    this.result = '';
                    this.statusUpdate = '';
                    this.showLogModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeLogModal() {
                    this.showLogModal = false;
                    document.body.style.overflow = '';
                },

                startTutorial() {
                    this.tutorialStep = 0;
                    this.showTutorial = true;
                },

                dismissTutorialHint() {
                    this.showTutorialHint = false;
                    localStorage.setItem('leads_tutorial_dismissed', 'true');
                },

                completeTutorial() {
                    this.showTutorial = false;
                    localStorage.setItem('leads_tutorial_done', 'true');
                    this.dismissTutorialHint();
                }
            }
        }
    </script>

    <style>
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
    </style>
</x-app-layout>
