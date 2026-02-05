<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Leads
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Lead</h1>
            <p class="text-sm text-gray-500 mt-1">Update lead information and status</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Contact Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $lead->name) }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                            <input type="text" name="phone" id="phone" required value="{{ old('phone', $lead->phone) }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                        
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                            <input type="text" name="country" id="country" required value="{{ old('country', $lead->country) }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                    </div>
                </div>

                <!-- Lead Status -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Lead Status</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="lead_status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="lead_status" id="lead_status" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="new" {{ old('lead_status', $lead->lead_status) == 'new' ? 'selected' : '' }}>New</option>
                                <option value="assigned" {{ old('lead_status', $lead->lead_status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="contacted" {{ old('lead_status', $lead->lead_status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="negotiating" {{ old('lead_status', $lead->lead_status) == 'negotiating' ? 'selected' : '' }}>Negotiating</option>
                                <option value="converted" {{ old('lead_status', $lead->lead_status) == 'converted' ? 'selected' : '' }}>Converted</option>
                                <option value="lost" {{ old('lead_status', $lead->lead_status) == 'lost' ? 'selected' : '' }}>Lost</option>
                                <option value="not_interested" {{ old('lead_status', $lead->lead_status) == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="lead_quality" class="block text-sm font-medium text-gray-700 mb-1">Quality *</label>
                            <select name="lead_quality" id="lead_quality" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="hot" {{ old('lead_quality', $lead->lead_quality) == 'hot' ? 'selected' : '' }}>🔥 Hot</option>
                                <option value="warm" {{ old('lead_quality', $lead->lead_quality) == 'warm' ? 'selected' : '' }}>☀️ Warm</option>
                                <option value="cold" {{ old('lead_quality', $lead->lead_quality) == 'cold' ? 'selected' : '' }}>❄️ Cold</option>
                                <option value="invalid" {{ old('lead_quality', $lead->lead_quality) == 'invalid' ? 'selected' : '' }}>⚠️ Invalid</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Source *</label>
                            <select name="source" id="source" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="whatsapp" {{ old('source', $lead->source) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="call" {{ old('source', $lead->source) == 'call' ? 'selected' : '' }}>Phone Call</option>
                                <option value="website" {{ old('source', $lead->source) == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>Referral</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Conversion Section (shown when status is converted) -->
                <div id="conversionSection" class="space-y-4 pt-4 border-t border-gray-100 {{ old('lead_status', $lead->lead_status) == 'converted' ? '' : 'hidden' }}">
                    <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wider">Conversion Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="medicine_id" class="block text-sm font-medium text-gray-700 mb-1">Medicine</label>
                            <select name="medicine_id" id="medicine_id" class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="">Select Medicine</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="packs_ordered" class="block text-sm font-medium text-gray-700 mb-1">Packs Ordered</label>
                            <input type="number" name="packs_ordered" id="packs_ordered" min="1" value="{{ old('packs_ordered', 1) }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                    </div>
                </div>

                <!-- Assignment -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Assignment</h3>
                    
                    <div>
                        <label for="representative_id" class="block text-sm font-medium text-gray-700 mb-1">Assigned Representative</label>
                        <select name="representative_id" id="representative_id" class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Unassigned</option>
                            @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}" {{ old('representative_id', $lead->representative_id) == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->user->name }} ({{ $rep->country }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Notes -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Notes</h3>
                    
                    <div>
                        <textarea name="notes" id="notes" rows="4"
                                  class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500"
                                  placeholder="Additional notes about this lead...">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.leads.index') }}" 
                       class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-slate-900 text-white text-center font-medium rounded-lg hover:bg-slate-800 transition-colors">
                        Update Lead
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="mt-6 bg-red-50 rounded-xl border border-red-100 p-6">
            <h3 class="text-sm font-semibold text-red-700 uppercase tracking-wider mb-3">Danger Zone</h3>
            <p class="text-sm text-red-600 mb-4">Deleting this lead will permanently remove all associated data including activities and orders.</p>
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Are you sure you want to delete this lead? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Delete Lead
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('lead_status').addEventListener('change', function() {
            const conversionSection = document.getElementById('conversionSection');
            if (this.value === 'converted') {
                conversionSection.classList.remove('hidden');
            } else {
                conversionSection.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
