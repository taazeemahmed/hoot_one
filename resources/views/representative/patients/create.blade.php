<x-app-layout>
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('representative.patients.index') }}" class="p-2 text-corp-400 hover:text-corp-700 hover:bg-corp-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-corp-900">Add Patient</h1>
                <p class="text-sm text-corp-400">Create a new patient record</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
            <form action="{{ route('representative.patients.store') }}" method="POST">
                @csrf
                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Patient Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Email Address (Optional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Phone</label>
                            <div class="flex gap-2">
                                <select name="country_code" class="w-28 border border-corp-200 rounded-xl py-2.5 px-2 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green bg-corp-50">
                                    @foreach($countryCodes as $code)
                                        <option value="{{ $code['code'] }}" {{ $code['code'] === '+91' ? 'selected' : '' }}>{{ $code['code'] }} ({{ $code['country'] }})</option>
                                    @endforeach
                                </select>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Mobile Number" required class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            </div>
                            @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Country</label>
                            <input type="text" name="country" value="{{ old('country', auth()->user()->representative->country ?? '') }}" required class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            @error('country') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Address</label>
                            <textarea name="address" rows="3" class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">{{ old('address') }}</textarea>
                            @error('address') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Notes (Medical History/Comments)</label>
                            <textarea name="notes" rows="3" placeholder="Any relevant medical history or comments..." class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">{{ old('notes') }}</textarea>
                            @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="px-5 py-4 bg-corp-50 border-t border-corp-100 flex items-center justify-end gap-3">
                    <a href="{{ route('representative.patients.index') }}" class="px-5 py-2.5 text-sm font-medium text-corp-600 hover:text-corp-800 transition-colors">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-corp-900 hover:bg-corp-800 rounded-xl transition-colors shadow-sm">Create Patient</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
