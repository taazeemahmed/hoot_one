<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lead Management</h1>
                <p class="text-sm text-gray-500 mt-1">Manage all leads including Company Direct</p>
            </div>
            <a href="{{ route('admin.leads.create') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Company Direct Lead
            </a>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-gray-900">{{ $metrics['total'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Total Leads</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $metrics['new'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">New</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-amber-600">{{ $metrics['assigned'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Assigned</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-purple-600">{{ $metrics['negotiating'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Negotiating</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-orange-600">{{ $metrics['converted'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Converted</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-orange-600">{{ $metrics['hot'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Hot Leads</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-200 text-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="negotiating" {{ request('status') == 'negotiating' ? 'selected' : '' }}>Negotiating</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Quality</label>
                    <select name="quality" class="w-full rounded-lg border-gray-200 text-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="">All Quality</option>
                        <option value="hot" {{ request('quality') == 'hot' ? 'selected' : '' }}>Hot</option>
                        <option value="warm" {{ request('quality') == 'warm' ? 'selected' : '' }}>Warm</option>
                        <option value="cold" {{ request('quality') == 'cold' ? 'selected' : '' }}>Cold</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Source</label>
                    <select name="source_user" class="w-full rounded-lg border-gray-200 text-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="">All Sources</option>
                        <option value="company_direct" {{ request('source_user') == 'company_direct' ? 'selected' : '' }}>Company Direct</option>
                        @foreach($marketingMembers as $member)
                            <option value="{{ $member->id }}" {{ request('source_user') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Representative</label>
                    <select name="representative_id" class="w-full rounded-lg border-gray-200 text-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="">All Representatives</option>
                        @foreach($representatives as $rep)
                            <option value="{{ $rep->id }}" {{ request('representative_id') == $rep->id ? 'selected' : '' }}>{{ $rep->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Leads Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lead</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quality</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Representative</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Source</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($lead->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $lead->country }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $lead->phone }}</div>
                                <div class="text-xs text-gray-500">{{ $lead->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'new' => 'bg-blue-100 text-blue-700',
                                        'assigned' => 'bg-amber-100 text-amber-700',
                                        'contacted' => 'bg-cyan-100 text-cyan-700',
                                        'negotiating' => 'bg-purple-100 text-purple-700',
                                        'converted' => 'bg-orange-100 text-orange-700',
                                        'lost' => 'bg-red-100 text-red-700',
                                        'not_interested' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$lead->lead_status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $lead->lead_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $qualityIcons = [
                                        'hot' => ['icon' => '🔥', 'class' => 'bg-orange-100 text-orange-700'],
                                        'warm' => ['icon' => '☀️', 'class' => 'bg-amber-100 text-amber-700'],
                                        'cold' => ['icon' => '❄️', 'class' => 'bg-blue-100 text-blue-700'],
                                        'invalid' => ['icon' => '⚠️', 'class' => 'bg-gray-100 text-gray-700'],
                                    ];
                                    $quality = $qualityIcons[$lead->lead_quality] ?? $qualityIcons['cold'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $quality['class'] }}">
                                    {{ $quality['icon'] }} {{ ucfirst($lead->lead_quality ?? 'cold') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($lead->representative)
                                    <div class="text-sm text-gray-900">{{ $lead->representative->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $lead->representative->country }}</div>
                                @else
                                    <span class="text-xs text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ ucfirst($lead->source ?? 'Direct') }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($lead->assignedBy)
                                        by {{ $lead->assignedBy->name }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $lead->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $lead->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.leads.show', $lead) }}"
                                       class="p-2 text-gray-400 hover:text-slate-600 hover:bg-gray-100 rounded-lg transition-colors"
                                       title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.leads.edit', $lead) }}"
                                       class="p-2 text-gray-400 hover:text-slate-600 hover:bg-gray-100 rounded-lg transition-colors"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if(!$lead->representative_id)
                                    <button onclick="openAssignModal({{ $lead->id }})"
                                            class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors"
                                            title="Assign">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No leads found</p>
                                    <p class="text-xs mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $leads->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" onclick="closeAssignModal()"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assign Lead to Representative</h3>
                <form id="assignForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Representative</label>
                        <select name="representative_id" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                            @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}">{{ $rep->user->name }} ({{ $rep->country }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeAssignModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAssignModal(leadId) {
            document.getElementById('assignForm').action = `/admin/leads/${leadId}/assign`;
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
