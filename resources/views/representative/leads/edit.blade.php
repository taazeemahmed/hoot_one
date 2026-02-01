<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Edit Lead</h3>
            <p class="mt-1 text-sm text-gray-500">Update details for {{ $lead->name }}</p>
        </div>
        <a href="{{ route('representative.leads.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Back to Leads</a>
    </div>

    <div class="w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <form action="{{ route('representative.leads.update', $lead) }}" method="POST" x-data="{ status: '{{ $lead->lead_status }}' }">
            @csrf
            @method('PUT')
            
            <div class="p-8">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $lead->name) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors" required>
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', $lead->country) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors" required>
                         @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-gray-200">

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Quality -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lead Quality</label>
                            <select name="lead_quality" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors">
                                <option value="hot" {{ old('lead_quality', $lead->lead_quality) == 'hot' ? 'selected' : '' }}>🔥 Hot</option>
                                <option value="warm" {{ old('lead_quality', $lead->lead_quality) == 'warm' ? 'selected' : '' }}>☀️ Warm</option>
                                <option value="cold" {{ old('lead_quality', $lead->lead_quality) == 'cold' ? 'selected' : '' }}>❄️ Cold</option>
                            </select>
                        </div>
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                             <select name="lead_status" x-model="status" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors">
                                <option value="new">New</option>
                                <option value="assigned">Assigned</option>
                                <option value="contacted">Contacted</option>
                                <option value="negotiating">Negotiating</option>
                                <option value="converted">✅ Converted (To Patient)</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>
                    </div>

                    <!-- Conversion Fields -->
                    <div x-show="status === 'converted'" class="bg-green-50 p-6 rounded-xl border border-green-200" x-transition>
                        <h4 class="font-bold text-green-800 mb-4 flex items-center text-lg">
                            <span class="mr-2">🎉</span> Conversion Details
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Medicine Prescribed</label>
                                <select name="medicine_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-colors">
                                    <option value="">Select Medicine...</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Packs (Initial Order)</label>
                                <input type="number" name="packs_ordered" min="1" value="1" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-colors">
                            </div>
                        </div>
                        <p class="text-sm text-green-700 mt-4 bg-white/50 p-3 rounded-lg">
                            <strong>Note:</strong> Saving as "Converted" will move this lead to your <strong>My Patients</strong> list and create an initial active order.
                        </p>
                    </div>

                    <!-- Notes -->
                    <div>
                         <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
                         <textarea name="notes" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-colors">{{ old('notes', $lead->notes) }}</textarea>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-4 border-t border-gray-100">
                <a href="{{ route('representative.leads.index') }}" class="px-6 py-2.5 font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 shadow-lg shadow-purple-500/30 transition-all transform active:scale-95">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
