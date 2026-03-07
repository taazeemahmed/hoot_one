<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="campaignManager()">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.campaigns.index') }}" class="inline-flex items-center text-sm text-corp-500 hover:text-corp-700 transition-colors mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Campaigns
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-corp-900">{{ $campaign->name }}</h3>
                    <div class="flex items-center gap-3 mt-1">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'sending' => 'bg-amber-100 text-amber-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'failed' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$campaign->status] }}">
                            {{ ucfirst($campaign->status) }}
                        </span>
                        <span class="text-sm text-corp-400">{{ $campaign->recipients->count() }} recipient(s) &bull; {{ $pendingCount }} pending</span>
                        @if($campaign->media_url)
                        <span class="inline-flex items-center gap-1 text-xs text-corp-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Image attached
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($campaign->status !== 'draft')
                    <a href="{{ route('admin.campaigns.logs', $campaign) }}" class="px-4 py-2.5 bg-white border border-corp-200 text-corp-600 text-sm font-medium rounded-xl hover:bg-corp-50 transition-colors">
                        View Logs
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Send Campaign Section -->
        @if($campaign->status !== 'sending' && $pendingCount > 0)
        <div class="bg-white rounded-xl border border-corp-100 shadow-sm p-5 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h4 class="font-semibold text-corp-900">Ready to Send</h4>
                    <p class="text-sm text-corp-400 mt-0.5">{{ $pendingCount }} pending recipient(s) will receive the WhatsApp message via "{{ $campaign->name }}" campaign</p>
                    @if($campaign->media_url)
                    <div class="flex items-center gap-2 mt-2">
                        <img src="{{ $campaign->media_url }}" alt="Campaign media" class="w-10 h-10 rounded-lg object-cover border border-corp-200">
                        <span class="text-xs text-corp-500">{{ $campaign->media_filename ?? 'Image attached' }}</span>
                    </div>
                    @endif
                </div>
                <button @click="sendCampaign()" :disabled="sending"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg x-show="!sending" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="sending ? 'Sending...' : 'Send Campaign'"></span>
                </button>
            </div>

            <!-- Progress Bar -->
            <div x-show="sending || sendComplete" x-cloak class="mt-4">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-corp-600 font-medium" x-text="progressText"></span>
                    <span class="text-corp-400" x-text="progressPercent + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-300 ease-out"
                         :class="sendComplete ? (sendResult.failed > 0 ? 'bg-amber-500' : 'bg-green-500') : 'bg-corp-600'"
                         :style="'width: ' + progressPercent + '%'"></div>
                </div>
                <div x-show="sendComplete" class="mt-3 flex gap-4 text-sm" x-cloak>
                    <span class="text-green-600 font-medium"><span x-text="sendResult.sent"></span> sent</span>
                    <span class="text-red-600 font-medium"><span x-text="sendResult.failed"></span> failed</span>
                    <span class="text-amber-600 font-medium"><span x-text="sendResult.skipped"></span> skipped</span>
                </div>
            </div>
        </div>
        @endif

        @if($campaign->status === 'sending')
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-6" x-init="pollProgress()">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-5 h-5 text-amber-600 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span class="font-semibold text-amber-800">Campaign is sending...</span>
            </div>
            <div class="w-full bg-amber-200 rounded-full h-3 overflow-hidden">
                <div class="bg-amber-600 h-3 rounded-full transition-all duration-500" :style="'width: ' + progressPercent + '%'"></div>
            </div>
            <p class="text-sm text-amber-700 mt-2" x-text="progressText"></p>
        </div>
        @endif

        @if($campaign->success_count > 0 || $campaign->failed_count > 0 || $campaign->skipped_count > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-semibold text-blue-800">Cumulative Send Stats (all batches)</span>
                </div>
                <div class="flex gap-4 text-sm">
                    <span class="text-green-700 font-medium">{{ $campaign->success_count }} delivered</span>
                    <span class="text-red-600 font-medium">{{ $campaign->failed_count }} failed</span>
                    <span class="text-amber-600 font-medium">{{ $campaign->skipped_count }} skipped</span>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Patient Selection (2/3 width) -->
            @if($campaign->status !== 'sending')
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-corp-100">
                        <h4 class="font-semibold text-corp-900">Select Patients</h4>
                        <p class="text-xs text-corp-400 mt-0.5">Filter and select patients to add to this campaign</p>
                    </div>

                    <!-- Filters -->
                    <div class="p-4 border-b border-corp-50 bg-corp-50/50">
                        <form method="GET" action="{{ route('admin.campaigns.show', $campaign) }}">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or phone..."
                                           class="w-full border-corp-200 rounded-lg text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50 h-10">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Representative</label>
                                    <select name="representative_id" class="w-full border-corp-200 rounded-lg text-sm h-10 bg-white">
                                        <option value="">All Reps</option>
                                        @foreach($representatives as $rep)
                                            <option value="{{ $rep->id }}" {{ request('representative_id') == $rep->id ? 'selected' : '' }}>{{ $rep->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Medicine</label>
                                    <select name="medicine_id" class="w-full border-corp-200 rounded-lg text-sm h-10 bg-white">
                                        <option value="">All Medicines</option>
                                        @foreach($medicines as $medicine)
                                            <option value="{{ $medicine->id }}" {{ request('medicine_id') == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Patient Status</label>
                                    <select name="order_status" class="w-full border-corp-200 rounded-lg text-sm h-10 bg-white">
                                        <option value="">All</option>
                                        <option value="active" {{ request('order_status') == 'active' ? 'selected' : '' }}>Active Orders</option>
                                        <option value="overdue" {{ request('order_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Renewal From</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                           class="w-full border-corp-200 rounded-lg text-sm h-10">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-corp-500 uppercase tracking-wider mb-1">Renewal To</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                           class="w-full border-corp-200 rounded-lg text-sm h-10">
                                </div>
                                <div class="flex items-end gap-2 col-span-2">
                                    <button type="submit" class="px-4 py-2.5 bg-corp-900 text-white text-sm font-semibold rounded-lg hover:bg-corp-800 transition-colors">
                                        Filter
                                    </button>
                                    <a href="{{ route('admin.campaigns.show', $campaign) }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Patient List with Checkboxes -->
                    <form method="POST" action="{{ route('admin.campaigns.add-recipients', $campaign) }}" id="addRecipientsForm">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left">
                                            <input type="checkbox" id="selectAll" @change="toggleAll($event)"
                                                   class="rounded border-gray-300 text-corp-600 focus:ring-corp-500">
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Representative</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Medicine</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($patients as $patient)
                                    @php
                                        $phone = preg_replace('/[^0-9]/', '', $patient->phone);
                                        $alreadyAdded = in_array($phone, $existingPhones);
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 {{ $alreadyAdded ? 'opacity-50' : '' }}">
                                        <td class="px-4 py-3">
                                            @if(!$alreadyAdded)
                                            <input type="checkbox" name="patient_ids[]" value="{{ $patient->id }}"
                                                   class="patient-checkbox rounded border-gray-300 text-corp-600 focus:ring-corp-500">
                                            @else
                                            <span class="text-xs text-green-600" title="Already added">&#10003;</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 text-sm">{{ $patient->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $patient->country }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $patient->phone }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $patient->representative->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $patient->latestOrder->medicine->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($patient->latestOrder)
                                                @php $renewal = $patient->latestOrder->renewal_status; @endphp
                                                @if($renewal === 'overdue')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Overdue</span>
                                                @elseif($renewal === 'urgent')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Urgent</span>
                                                @elseif($patient->latestOrder->status === 'active')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ ucfirst($patient->latestOrder->status) }}</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">No order</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No patients found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($patients->count() > 0)
                        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                            <div class="text-sm text-corp-500">
                                <span x-text="selectedCount"></span> selected
                            </div>
                            <button type="submit" :disabled="selectedCount === 0"
                                    class="px-5 py-2 bg-corp-600 hover:bg-corp-700 disabled:bg-corp-300 text-white text-sm font-semibold rounded-lg transition-colors">
                                Add Selected to Campaign
                            </button>
                        </div>
                        @endif

                        @if($patients->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100">
                            {{ $patients->links() }}
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @else
            <div class="lg:col-span-2"></div>
            @endif

            <!-- Right: Campaign Recipients (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-corp-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-corp-900">Campaign Recipients</h4>
                                <p class="text-xs text-corp-400 mt-0.5">{{ $campaign->recipients->count() }} total &bull; {{ $pendingCount }} pending</p>
                            </div>
                            @php $failedSkippedCount = $campaign->recipients->whereIn('status', ['failed', 'skipped'])->count(); @endphp
                            @if($failedSkippedCount > 0 && $campaign->status !== 'sending')
                            <form method="POST" action="{{ route('admin.campaigns.retry-failed', $campaign) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg hover:bg-amber-200 transition-colors" title="Reset failed/skipped to pending for retry">
                                    Retry Failed ({{ $failedSkippedCount }})
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    @if($campaign->recipients->count() > 0)
                    <div class="divide-y divide-gray-50 max-h-[600px] overflow-y-auto">
                        @foreach($campaign->recipients as $recipient)
                        <div class="px-4 py-3 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $recipient->patient->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $recipient->phone }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($recipient->status === 'pending')
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                @elseif($recipient->status === 'success')
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                @elseif($recipient->status === 'failed')
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                @elseif($recipient->status === 'skipped')
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                @endif
                                @if($campaign->status !== 'sending' && $recipient->status === 'pending')
                                <form method="POST" action="{{ route('admin.campaigns.remove-recipient', [$campaign, $recipient]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition-colors" title="Remove">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <p class="text-sm">No recipients added yet</p>
                        <p class="text-xs mt-1">Use filters to find patients and add them</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function campaignManager() {
            return {
                selectedCount: 0,
                sending: false,
                sendComplete: false,
                sendResult: { sent: 0, failed: 0, skipped: 0 },
                progressPercent: 0,
                progressText: '',

                init() {
                    this.updateSelectedCount();
                    document.querySelectorAll('.patient-checkbox').forEach(cb => {
                        cb.addEventListener('change', () => this.updateSelectedCount());
                    });
                },

                toggleAll(event) {
                    const checked = event.target.checked;
                    document.querySelectorAll('.patient-checkbox').forEach(cb => {
                        cb.checked = checked;
                    });
                    this.updateSelectedCount();
                },

                updateSelectedCount() {
                    this.selectedCount = document.querySelectorAll('.patient-checkbox:checked').length;
                },

                async sendCampaign() {
                    if (!confirm('Are you sure you want to send this campaign to ' + {{ $pendingCount }} + ' pending recipient(s)?')) return;

                    this.sending = true;
                    this.sendComplete = false;
                    this.progressPercent = 0;
                    this.progressText = 'Starting campaign...';

                    // Start polling progress
                    const pollInterval = setInterval(async () => {
                        try {
                            const res = await fetch('{{ route("admin.campaigns.progress", $campaign) }}');
                            const data = await res.json();
                            this.progressPercent = data.progress_percent;
                            this.progressText = `Sent ${data.sent_count} of ${data.total} (${data.success_count} success, ${data.failed_count} failed, ${data.skipped_count} skipped)`;

                            if (data.status !== 'sending') {
                                clearInterval(pollInterval);
                            }
                        } catch(e) {}
                    }, 1000);

                    try {
                        const response = await fetch('{{ route("admin.campaigns.send", $campaign) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();
                        clearInterval(pollInterval);

                        if (result.success) {
                            this.sendResult = result;
                            this.progressPercent = 100;
                            this.progressText = 'Campaign completed!';
                            this.sendComplete = true;
                            // Reload after brief delay to show updated status
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            this.progressText = 'Error: ' + (result.error || 'Unknown error');
                            alert(result.error || 'Failed to send campaign');
                        }
                    } catch (error) {
                        clearInterval(pollInterval);
                        this.progressText = 'Network error occurred';
                        alert('Network error: ' + error.message);
                    }

                    this.sending = false;
                },

                async pollProgress() {
                    const poll = async () => {
                        try {
                            const res = await fetch('{{ route("admin.campaigns.progress", $campaign) }}');
                            const data = await res.json();
                            this.progressPercent = data.progress_percent;
                            this.progressText = `Sent ${data.sent_count} of ${data.total} (${data.success_count} success, ${data.failed_count} failed)`;

                            if (data.status !== 'sending') {
                                window.location.reload();
                                return;
                            }
                            setTimeout(poll, 1500);
                        } catch(e) {
                            setTimeout(poll, 3000);
                        }
                    };
                    poll();
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
