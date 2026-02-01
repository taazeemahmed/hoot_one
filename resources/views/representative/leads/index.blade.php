<x-app-layout>
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h3 class="text-2xl sm:text-3xl font-medium text-gray-700">Pending Leads</h3>
            <p class="mt-1 text-sm text-gray-500">Manage your assigned leads and log interactions.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Mobile Cards -->
    <div class="space-y-4 sm:hidden">
        @forelse($leads as $lead)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4" x-data="{ openLog: false, statusUpdate: '' }">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $lead->name }}</p>
                        <p class="text-xs text-gray-600">{{ $lead->phone }}</p>
                        <p class="text-xs text-gray-500">{{ $lead->country }} | {{ $lead->email }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $lead->lead_status === 'new' || $lead->lead_status === 'assigned' ? 'text-orange-700 bg-orange-100' : 'text-green-700 bg-green-100' }}">
                        {{ ucfirst($lead->lead_status) }}
                    </span>
                </div>

                <div class="mt-2 text-xs text-gray-400">Assigned: {{ $lead->assigned_at ? $lead->assigned_at->diffForHumans() : 'N/A' }}</div>

                <div class="mt-3 text-xs text-gray-600">
                    <span class="bg-blue-100 text-blue-800 font-semibold px-2 py-1 rounded">{{ $lead->lead_quality ?? 'warm' }}</span>
                    <div class="mt-2">
                        @if($lead->latestActivity)
                            <span class="font-semibold text-gray-700">{{ ucfirst($lead->latestActivity->type) }}:</span> {{ $lead->latestActivity->result }}
                            <span class="block text-gray-400">{{ $lead->latestActivity->created_at->diffForHumans() }}</span>
                        @else
                            <span class="text-gray-400 italic">No activity yet</span>
                        @endif
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('representative.leads.edit', $lead) }}" class="p-2 text-white bg-amber-500 rounded-lg text-xs">Edit</a>
                    <a href="https://wa.me/{{ $lead->phone }}" target="_blank" class="p-2 text-white bg-green-500 rounded-lg text-xs">WhatsApp</a>
                    <a href="tel:{{ $lead->phone }}" class="p-2 text-white bg-blue-500 rounded-lg text-xs">Call</a>
                    <button @click="openLog = true" class="px-3 py-2 text-xs font-medium text-white bg-purple-600 rounded-lg">Log Activity</button>
                </div>

                <!-- Modal (Mobile) -->
                <div x-show="openLog" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" @click="openLog = false">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            <form action="{{ route('representative.leads.activity.store', $lead) }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6 bg-purple-50 p-2 rounded-lg border-l-4 border-purple-500">Log Activity for {{ $lead->name }}</h3>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Activity Type</label>
                                            <select name="type" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200">
                                                <option value="call">Call</option>
                                                <option value="whatsapp">WhatsApp Message</option>
                                                <option value="note">General Note</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Lead Quality</label>
                                            <select name="lead_quality" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200">
                                                <option value="hot" {{ $lead->lead_quality === 'hot' ? 'selected' : '' }}>🔥 Hot</option>
                                                <option value="warm" {{ $lead->lead_quality === 'warm' ? 'selected' : '' }}>☀️ Warm</option>
                                                <option value="cold" {{ $lead->lead_quality === 'cold' ? 'selected' : '' }}>❄️ Cold</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Result/Outcome</label>
                                        <input type="text" name="result" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="e.g. Interested, No Answer" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Detailed Notes</label>
                                        <textarea name="notes" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200" rows="3"></textarea>
                                    </div>

                                    <hr class="border-gray-200 my-4">

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Update Status</label>
                                        <select name="status_update" x-model="statusUpdate" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200">
                                            <option value="">-- Keep Current Status --</option>
                                            <option value="contacted">Contacted</option>
                                            <option value="negotiating">Negotiating</option>
                                            <option value="converted">✅ Convert to Patient</option>
                                            <option value="lost">Lost</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1" x-show="statusUpdate === 'converted'">
                                            <span class="text-green-600 font-bold">Note:</span> This will move the lead to your "My Patients" list.
                                        </p>
                                    </div>

                                    <div x-show="statusUpdate === 'converted'" class="bg-green-50 p-4 rounded-lg border border-green-200">
                                        <h4 class="font-bold text-green-800 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Initial Order Details
                                        </h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-gray-700 text-xs font-bold mb-1">Medicine Given</label>
                                                <select name="medicine_id" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-200">
                                                    <option value="">Select Medicine...</option>
                                                    @foreach($medicines as $med)
                                                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-xs font-bold mb-1">Packs Created</label>
                                                <input type="number" name="packs_ordered" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-200" min="1" value="1">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm shadow-purple-200">
                                        Save Activity & Update
                                    </button>
                                    <button type="button" @click="openLog = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-sm text-center text-gray-500 bg-white rounded-lg p-6">
                No pending leads assigned to you.
            </div>
        @endforelse
        <div class="px-2">
            {{ $leads->links() }}
        </div>
    </div>

    <!-- Table (Desktop) -->
    <div class="hidden sm:block w-full overflow-hidden rounded-lg shadow-xs bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Lead Details</th>
                        <th class="px-4 py-3">Interest</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Latest Activity</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($leads as $lead)
                    <tr class="text-gray-700" x-data="{ openLog: false, statusUpdate: '' }">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $lead->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $lead->phone }}</p>
                                    <p class="text-xs text-gray-500">{{ $lead->country }} | {{ $lead->email }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Assigned: {{ $lead->assigned_at ? $lead->assigned_at->diffForHumans() : 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                {{ $lead->lead_quality ?? 'warm' }}
                            </span>
                            <div class="mt-1 text-xs text-gray-500">{{ $lead->notes ?? 'No notes' }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                             <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $lead->lead_status === 'new' || $lead->lead_status === 'assigned' ? 'text-orange-700 bg-orange-100' : 'text-green-700 bg-green-100' }}">
                                {{ ucfirst($lead->lead_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate">
                            @if($lead->latestActivity)
                                <div class="text-xs">
                                    <span class="font-bold">{{ ucfirst($lead->latestActivity->type) }}:</span> {{ $lead->latestActivity->result }}
                                    <span class="block text-gray-400">{{ $lead->latestActivity->created_at->diffForHumans() }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">No activity yet</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center flex-wrap gap-2">
                                <a href="{{ route('representative.leads.edit', $lead) }}" class="p-2 text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition-colors" title="Edit Lead">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <a href="https://wa.me/{{ $lead->phone }}" target="_blank" class="p-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition-colors" title="WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487 2.982 1.288 2.982.858 3.526.801.544-.056 1.729-.706 1.977-1.387.248-.68.248-1.264.173-1.387z"/></svg>
                                </a>
                                <a href="tel:{{ $lead->phone }}" class="p-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors" title="Call">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </a>
                                <button @click="openLog = true" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                    Log Activity
                                </button>
                            </div>

                            <!-- Modal -->
                            <div x-show="openLog" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" @click="openLog = false">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                        <form action="{{ route('representative.leads.activity.store', $lead) }}" method="POST">
                                            @csrf
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6 bg-purple-50 p-2 rounded-lg border-l-4 border-purple-500">Log Activity for {{ $lead->name }}</h3>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Activity Type</label>
                                                        <select name="type" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200">
                                                            <option value="call">Call</option>
                                                            <option value="whatsapp">WhatsApp Message</option>
                                                            <option value="note">General Note</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Lead Quality</label>
                                                        <select name="lead_quality" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200">
                                                            <option value="hot" {{ $lead->lead_quality === 'hot' ? 'selected' : '' }}>🔥 Hot</option>
                                                            <option value="warm" {{ $lead->lead_quality === 'warm' ? 'selected' : '' }}>☀️ Warm</option>
                                                            <option value="cold" {{ $lead->lead_quality === 'cold' ? 'selected' : '' }}>❄️ Cold</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Result/Outcome</label>
                                                    <input type="text" name="result" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="e.g. Interested, No Answer" required>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Detailed Notes</label>
                                                    <textarea name="notes" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200" rows="3"></textarea>
                                                </div>

                                                <hr class="border-gray-200 my-4">

                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-xs font-bold uppercase tracking-wide mb-2">Update Status</label>
                                                    <select name="status_update" x-model="statusUpdate" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-200">
                                                        <option value="">-- Keep Current Status --</option>
                                                        <option value="contacted">Contacted</option>
                                                        <option value="negotiating">Negotiating</option>
                                                        <option value="converted">✅ Convert to Patient</option>
                                                        <option value="lost">Lost</option>
                                                    </select>
                                                    <p class="text-xs text-gray-500 mt-1" x-show="statusUpdate === 'converted'">
                                                        <span class="text-green-600 font-bold">Note:</span> This will move the lead to your "My Patients" list.
                                                    </p>
                                                </div>

                                                <!-- Conditional Order Fields -->
                                                <div x-show="statusUpdate === 'converted'" class="bg-green-50 p-4 rounded-lg border border-green-200">
                                                    <h4 class="font-bold text-green-800 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                        Initial Order Details
                                                    </h4>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-gray-700 text-xs font-bold mb-1">Medicine Given</label>
                                                            <select name="medicine_id" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-200">
                                                                <option value="">Select Medicine...</option>
                                                                @foreach($medicines as $med)
                                                                    <option value="{{ $med->id }}">{{ $med->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 text-xs font-bold mb-1">Packs Created</label>
                                                            <input type="number" name="packs_ordered" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-200" min="1" value="1">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm shadow-purple-200">
                                                    Save Activity & Update
                                                </button>
                                                <button type="button" @click="openLog = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">
                            No pending leads assigned to you.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $leads->links() }}
        </div>
    </div>
</x-app-layout>
