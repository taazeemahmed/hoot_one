<x-app-layout>
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('representative.leads.index') }}" class="p-2 text-corp-400 hover:text-corp-700 hover:bg-corp-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-corp-900">Edit Lead</h1>
                <p class="text-sm text-corp-400">Update details for {{ $lead->name }}</p>
            </div>
        </div>

        <div class="w-full max-w-2xl mx-auto bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
            <form action="{{ route('representative.leads.update', $lead) }}" method="POST" x-data="{ status: '{{ $lead->lead_status }}' }">
                @csrf
                @method('PUT')

                <div class="p-5 space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-corp-700 mb-1.5">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $lead->name) }}" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green" required>
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-corp-700 mb-1.5">Country</label>
                        <input type="text" name="country" value="{{ old('country', $lead->country) }}" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green" required>
                        @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-corp-100">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Quality -->
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Lead Quality</label>
                            <select name="lead_quality" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                                <option value="hot" {{ old('lead_quality', $lead->lead_quality) == 'hot' ? 'selected' : '' }}>Hot</option>
                                <option value="warm" {{ old('lead_quality', $lead->lead_quality) == 'warm' ? 'selected' : '' }}>Warm</option>
                                <option value="cold" {{ old('lead_quality', $lead->lead_quality) == 'cold' ? 'selected' : '' }}>Cold</option>
                            </select>
                        </div>
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Status</label>
                            <select name="lead_status" x-model="status" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                                <option value="new">New</option>
                                <option value="assigned">Assigned</option>
                                <option value="contacted">Contacted</option>
                                <option value="negotiating">Negotiating</option>
                                <option value="converted">Converted (To Patient)</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>
                    </div>

                    <!-- Conversion Fields -->
                    <div x-show="status === 'converted'" class="bg-orange-50 p-5 rounded-xl border border-orange-200" x-transition>
                        <h4 class="font-semibold text-orange-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-hoot-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Conversion Details
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-corp-700 mb-1.5">Medicine Prescribed</label>
                                <select name="medicine_id" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                                    <option value="">Select Medicine...</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-corp-700 mb-1.5">Packs (Initial Order)</label>
                                <input type="number" name="packs_ordered" min="1" value="1" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                            </div>
                        </div>
                        <p class="text-sm text-orange-700 mt-4 bg-white/60 p-3 rounded-lg">
                            <strong>Note:</strong> Saving as "Converted" will move this lead to your <strong>My Patients</strong> list and create an initial active order.
                        </p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-corp-700 mb-1.5">Notes</label>
                        <textarea name="notes" rows="4" class="w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <div class="px-5 py-4 bg-corp-50 border-t border-corp-100 flex items-center justify-end gap-3">
                    <a href="{{ route('representative.leads.index') }}" class="px-5 py-2.5 text-sm font-medium text-corp-600 hover:text-corp-800 transition-colors">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-corp-900 hover:bg-corp-800 rounded-xl transition-colors shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
