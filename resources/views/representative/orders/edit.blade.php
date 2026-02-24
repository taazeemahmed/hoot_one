<x-app-layout>
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('representative.orders.index') }}" class="p-2 text-corp-400 hover:text-corp-700 hover:bg-corp-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-corp-900">Edit Order</h1>
                <p class="text-sm text-corp-400">Update order for {{ $order->patient->name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
            <form action="{{ route('representative.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Patient</label>
                            <input type="text" value="{{ $order->patient->name }}" disabled class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm bg-corp-50 text-corp-500 cursor-not-allowed">
                            <p class="text-xs text-corp-400 mt-1">Patient cannot be changed.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Medicine</label>
                            <select name="medicine_id" required class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ old('medicine_id', $order->medicine_id) == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }} ({{ $medicine->pack_duration_days }} days/pack)</option>
                                @endforeach
                            </select>
                            @error('medicine_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Quantity (Packs)</label>
                            <input type="number" name="packs_ordered" value="{{ old('packs_ordered', $order->packs_ordered) }}" min="1" required class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            @error('packs_ordered') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Treatment Start Date</label>
                            <input type="date" name="treatment_start_date" value="{{ old('treatment_start_date', $order->treatment_start_date->format('Y-m-d')) }}" required class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                            @error('treatment_start_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Status</label>
                            <select name="status" class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">
                                <option value="active" {{ old('status', $order->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-corp-700 mb-1.5">Notes</label>
                            <textarea name="notes" rows="3" class="block w-full border border-corp-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-hoot-green/20 focus:border-hoot-green">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="px-5 py-4 bg-corp-50 border-t border-corp-100 flex items-center justify-end gap-3">
                    <a href="{{ route('representative.orders.index') }}" class="px-5 py-2.5 text-sm font-medium text-corp-600 hover:text-corp-800 transition-colors">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-corp-900 hover:bg-corp-800 rounded-xl transition-colors shadow-sm">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
