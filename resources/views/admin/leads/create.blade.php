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
            <h1 class="text-2xl font-bold text-gray-900">Add Company Direct Lead</h1>
            <p class="text-sm text-gray-500 mt-1">Create a lead that comes directly to the company</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.leads.store') }}" class="space-y-6">
                @csrf
                
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Contact Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                            <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500 @error('phone') border-red-500 @enderror"
                                   placeholder="+91 98765 43210">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                        </div>
                        
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                            <input type="text" name="country" id="country" required value="{{ old('country') }}"
                                   class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500"
                                   placeholder="India">
                            @error('country')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Lead Details -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Lead Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Lead Source *</label>
                            <select name="source" id="source" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="">Select Source</option>
                                <option value="whatsapp" {{ old('source') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="call" {{ old('source') == 'call' ? 'selected' : '' }}>Phone Call</option>
                                <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="lead_quality" class="block text-sm font-medium text-gray-700 mb-1">Lead Quality *</label>
                            <select name="lead_quality" id="lead_quality" required class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                                <option value="">Select Quality</option>
                                <option value="hot" {{ old('lead_quality') == 'hot' ? 'selected' : '' }}>🔥 Hot</option>
                                <option value="warm" {{ old('lead_quality') == 'warm' ? 'selected' : '' }}>☀️ Warm</option>
                                <option value="cold" {{ old('lead_quality') == 'cold' ? 'selected' : '' }}>❄️ Cold</option>
                                <option value="invalid" {{ old('lead_quality') == 'invalid' ? 'selected' : '' }}>⚠️ Invalid</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="medical_concern" class="block text-sm font-medium text-gray-700 mb-1">Medical Concern *</label>
                        <textarea name="medical_concern" id="medical_concern" rows="2" required
                                  class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500"
                                  placeholder="What health issue is the patient inquiring about?">{{ old('medical_concern') }}</textarea>
                        @error('medical_concern')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500"
                                  placeholder="Any other relevant information...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Assignment -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Assignment (Optional)</h3>
                    
                    <div>
                        <label for="representative_id" class="block text-sm font-medium text-gray-700 mb-1">Assign to Representative</label>
                        <select name="representative_id" id="representative_id" class="w-full rounded-lg border-gray-200 focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Leave Unassigned</option>
                            @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}" {{ old('representative_id') == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->user->name }} ({{ $rep->country }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Leave empty to keep as unassigned Company Direct lead</p>
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
                        Create Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
