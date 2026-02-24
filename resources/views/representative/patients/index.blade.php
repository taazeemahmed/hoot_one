<x-app-layout>
    <div class="space-y-4">
        <!-- Page Header -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-corp-900">My Patients</h1>
                <p class="text-sm text-corp-400 mt-0.5">{{ $patients->total() }} patient{{ $patients->total() !== 1 ? 's' : '' }} in your portfolio</p>
            </div>
            <a href="{{ route('representative.patients.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-corp-900 text-white rounded-xl text-sm font-medium hover:bg-hoot-dark transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Add Patient
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-xl border border-corp-100 p-3 shadow-sm">
            <form action="{{ route('representative.patients.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-10 pr-3 py-2.5 border border-corp-200 rounded-xl text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green"
                           placeholder="Search by name, email or phone...">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-corp-700 hover:bg-corp-800 text-white rounded-xl text-sm font-medium transition-colors">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('representative.patients.index') }}" class="px-4 py-2.5 bg-corp-50 hover:bg-corp-100 text-corp-600 rounded-xl text-sm font-medium transition-colors border border-corp-200">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Patient Cards -->
        <div class="space-y-3">
            @forelse($patients as $patient)
                @php
                    $statusColors = [
                        'assigned' => 'bg-violet-50 text-violet-700 border-violet-200',
                        'converted' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'follow_up' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'contacted' => 'bg-corp-50 text-corp-600 border-corp-200',
                        'not_interested' => 'bg-red-50 text-red-600 border-red-200',
                    ];
                    $statusColor = $statusColors[$patient->lead_status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                @endphp
                <div class="bg-white rounded-xl border border-corp-100 overflow-hidden hover:shadow-sm transition-shadow">
                    <div class="p-4">
                        <!-- Top Row: Name + Status Badge -->
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-corp-900 truncate">{{ $patient->name }}</h3>
                                <p class="text-sm text-corp-400 flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $patient->country ?? 'N/A' }}
                                    @if($patient->email)
                                        <span class="text-corp-200">·</span>
                                        <span class="truncate">{{ $patient->email }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($patient->lead_quality)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full border
                                        {{ $patient->lead_quality === 'hot' ? 'bg-orange-50 text-orange-600 border-orange-200' : ($patient->lead_quality === 'cold' ? 'bg-corp-50 text-corp-500 border-corp-200' : 'bg-amber-50 text-amber-600 border-amber-200') }}">
                                        @if($patient->lead_quality === 'hot')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                        @elseif($patient->lead_quality === 'warm')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                        @endif
                                        {{ ucfirst($patient->lead_quality) }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $patient->lead_status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Phone copy row -->
                        <div class="flex items-center gap-2 mb-3 bg-corp-50 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-corp-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-sm font-mono text-corp-700 flex-1">{{ $patient->phone }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ $patient->phone }}').then(()=>{this.innerHTML='<svg class=\'w-4 h-4 text-orange-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>';setTimeout(()=>{this.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'/></svg>'},1500)})"
                                    class="p-1.5 text-corp-400 hover:text-corp-700 hover:bg-corp-100 rounded-md transition-colors" title="Copy phone number">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>

                        <!-- Meta info row -->
                        <div class="flex items-center gap-4 mb-3 text-sm text-corp-400">
                            @if($patient->source)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Source: {{ ucfirst($patient->source) }}
                            </span>
                            @endif
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ $patient->orders_count }} order{{ $patient->orders_count !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $patient->phone) }}" target="_blank"
                               class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                WhatsApp
                            </a>
                            <a href="tel:{{ $patient->phone }}"
                               class="w-11 h-11 inline-flex items-center justify-center bg-corp-600 hover:bg-corp-700 text-white rounded-lg transition-colors shadow-sm" title="Call">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </a>
                            <button onclick="openLogModal({{ $patient->id }}, '{{ $patient->name }}')"
                               class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-corp-900 hover:bg-corp-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Log Activity
                            </button>
                            <a href="{{ route('representative.patients.show', $patient) }}"
                               class="w-11 h-11 inline-flex items-center justify-center bg-corp-50 hover:bg-corp-100 text-corp-600 border border-corp-200 rounded-lg transition-colors" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('representative.patients.edit', $patient) }}"
                               class="w-11 h-11 inline-flex items-center justify-center bg-corp-50 hover:bg-corp-100 text-corp-600 border border-corp-200 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-corp-100 p-8 text-center">
                    <div class="w-16 h-16 bg-corp-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-corp-900 mb-1">No patients found</h3>
                    <p class="text-sm text-corp-400">Try adjusting your search or add a new patient.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($patients->hasPages())
            <div class="bg-white rounded-xl border border-corp-100 px-4 py-3">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

    <!-- Log Activity Bottom Sheet Modal -->
    <div id="logModal" class="fixed inset-0 z-50 hidden" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="absolute inset-0 bg-corp-900/40 backdrop-blur-sm"></div>
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl shadow-xl max-h-[85vh] overflow-y-auto sm:relative sm:top-1/2 sm:-translate-y-1/2 sm:max-w-lg sm:mx-auto sm:rounded-2xl sm:bottom-auto sm:left-auto sm:right-auto">
            <!-- Handle bar (mobile) -->
            <div class="flex justify-center pt-3 sm:hidden">
                <div class="w-10 h-1 bg-corp-200 rounded-full"></div>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-corp-900">Log Activity</h3>
                        <p class="text-sm text-corp-400">for <span id="logLeadName" class="font-semibold text-corp-700"></span></p>
                    </div>
                    <button onclick="document.getElementById('logModal').classList.add('hidden')" class="p-2 text-corp-400 hover:text-corp-700 hover:bg-corp-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="logForm" method="POST" action="{{ route('lead_activities.store') }}">
                    @csrf
                    <input type="hidden" name="patient_id" id="logPatientId">
                    <input type="hidden" name="type" value="call">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-corp-700 mb-1.5">Outcome</label>
                        <select name="result" id="logResult" onchange="toggleFollowUp()" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm text-corp-700 focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            <option value="info">General Info / Note</option>
                            <option value="follow_up">Follow Up Required</option>
                            <option value="not_reachable">Not Reachable</option>
                            <option value="not_interested">Not Interested</option>
                            <option value="converted">Converted (Ready for Order)</option>
                        </select>
                    </div>

                    <div class="mb-4 hidden" id="followUpContainer">
                        <label class="block text-sm font-medium text-corp-700 mb-1.5">Follow Up Date</label>
                        <input type="date" name="follow_up_at" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm text-corp-700 focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-corp-700 mb-1.5">Notes</label>
                        <textarea name="notes" rows="3" placeholder="Add your notes here..." class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm text-corp-700 focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('logModal').classList.add('hidden')" class="flex-1 py-2.5 px-4 bg-corp-50 hover:bg-corp-100 text-corp-600 rounded-xl text-sm font-medium transition-colors border border-corp-200">Cancel</button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-corp-900 hover:bg-corp-800 text-white rounded-xl text-sm font-medium transition-colors shadow-sm">Save Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLogModal(id, name) {
            document.getElementById('logLeadName').innerText = name;
            document.getElementById('logPatientId').value = id;
            document.getElementById('logModal').classList.remove('hidden');
        }

        function toggleFollowUp() {
            let result = document.getElementById('logResult').value;
            let container = document.getElementById('followUpContainer');
            if (result === 'follow_up') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
