<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Leads
                </a>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($lead->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $lead->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $lead->country }} &bull; Added {{ $lead->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                </a>
                <a href="tel:{{ $lead->phone }}"
                   class="inline-flex items-center px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Call
                </a>
                <a href="{{ route('admin.leads.edit', $lead) }}"
                   class="inline-flex items-center px-3 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Lead Info Card -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Lead Information</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</dt>
                                @php
                                    $statusColors = [
                                        'new' => 'bg-blue-100 text-blue-700',
                                        'assigned' => 'bg-amber-100 text-amber-700',
                                        'contacted' => 'bg-cyan-100 text-cyan-700',
                                        'negotiating' => 'bg-purple-100 text-purple-700',
                                        'converted' => 'bg-emerald-100 text-emerald-700',
                                        'lost' => 'bg-red-100 text-red-700',
                                        'not_interested' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$lead->lead_status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $lead->lead_status)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Quality</dt>
                                @php
                                    $qualityIcons = [
                                        'hot' => ['icon' => '🔥', 'class' => 'bg-orange-100 text-orange-700'],
                                        'warm' => ['icon' => '☀️', 'class' => 'bg-amber-100 text-amber-700'],
                                        'cold' => ['icon' => '❄️', 'class' => 'bg-blue-100 text-blue-700'],
                                        'invalid' => ['icon' => '⚠️', 'class' => 'bg-gray-100 text-gray-700'],
                                    ];
                                    $quality = $qualityIcons[$lead->lead_quality] ?? $qualityIcons['cold'];
                                @endphp
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $quality['class'] }}">
                                        {{ $quality['icon'] }} {{ ucfirst($lead->lead_quality ?? 'cold') }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $lead->phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $lead->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Country</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $lead->country }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Source</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($lead->source ?? 'Direct') }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Representative</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($lead->representative)
                                        {{ $lead->representative->user->name }} ({{ $lead->representative->country }})
                                    @else
                                        <span class="text-gray-400">Unassigned</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Added By</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($lead->assignedBy)
                                        {{ $lead->assignedBy->name }} ({{ ucfirst(str_replace('_', ' ', $lead->assignedBy->role)) }})
                                    @else
                                        <span class="text-gray-400">System</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        @if($lead->notes)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Notes</dt>
                            <dd class="text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 rounded-lg p-3">{{ $lead->notes }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900">Activity Timeline</h2>
                        <button onclick="document.getElementById('activityModal').classList.remove('hidden')"
                                class="text-sm text-slate-600 hover:text-slate-900 font-medium">
                            + Add Activity
                        </button>
                    </div>
                    <div class="p-6">
                        @if($lead->activities->count() > 0)
                        <div class="space-y-4">
                            @foreach($lead->activities as $activity)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    @php
                                        $typeColors = [
                                            'call' => 'bg-blue-100 text-blue-600',
                                            'whatsapp' => 'bg-green-100 text-green-600',
                                            'note' => 'bg-gray-100 text-gray-600',
                                            'assignment' => 'bg-purple-100 text-purple-600',
                                            'conversion' => 'bg-emerald-100 text-emerald-600',
                                        ];
                                    @endphp
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $typeColors[$activity->type] ?? 'bg-gray-100 text-gray-600' }}">
                                        @if($activity->type === 'call')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        @elseif($activity->type === 'whatsapp')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity->result }}</p>
                                        <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($activity->notes)
                                    <p class="mt-1 text-sm text-gray-500">{{ $activity->notes }}</p>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-400">by {{ $activity->user->name ?? 'System' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium">No activities yet</p>
                            <p class="text-xs mt-1">Add the first activity for this lead</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Orders -->
                @if($lead->orders->count() > 0)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Orders</h2>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($lead->orders as $order)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->medicine->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->packs_ordered }} pack(s) &bull; Started {{ $order->treatment_start_date->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Renewal: {{ $order->expected_renewal_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                @if(!$lead->representative_id)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <h3 class="font-semibold text-amber-800 mb-3">Assign Representative</h3>
                    <form method="POST" action="{{ route('admin.leads.assign', $lead) }}">
                        @csrf
                        <select name="representative_id" required class="w-full rounded-lg border-amber-200 text-sm mb-3 focus:border-amber-500 focus:ring-amber-500">
                            @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}">{{ $rep->user->name }} ({{ $rep->country }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                            Assign Now
                        </button>
                    </form>
                </div>
                @endif

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Activities</span>
                            <span class="font-medium text-gray-900">{{ $lead->activities->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Orders</span>
                            <span class="font-medium text-gray-900">{{ $lead->orders->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Days Since Created</span>
                            <span class="font-medium text-gray-900">{{ $lead->created_at->diffInDays(now()) }}</span>
                        </div>
                        @if($lead->assigned_at)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Days Since Assigned</span>
                            <span class="font-medium text-gray-900">{{ $lead->assigned_at->diffInDays(now()) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Modal -->
    <div id="activityModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('activityModal').classList.add('hidden')"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Log Activity</h3>
                <form method="POST" action="{{ route('admin.leads.activity.store', $lead) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                            <select name="type" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="call">Phone Call</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="note">Note</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Result</label>
                            <input type="text" name="result" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500"
                                   placeholder="e.g., Called, no answer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500"
                                      placeholder="Additional details..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date (Optional)</label>
                            <input type="date" name="follow_up_at" class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="document.getElementById('activityModal').classList.add('hidden')" 
                                class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
                            Log Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
