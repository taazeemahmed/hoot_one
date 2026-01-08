<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Edit Order</h3>
        <p class="mt-1 text-sm text-gray-500">Update order details</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                 <!-- Patient Display (Read Only) -->
                <div class="col-span-2 md:col-span-1">
                     <label class="block text-sm text-gray-700">Patient</label>
                     <input type="text" value="{{ $order->patient->name }} ({{ $order->patient->representative->user->name }})" disabled class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
                     <p class="text-xs text-gray-500 mt-1">Patient cannot be changed. Create a new order instead.</p>
                </div>

                <!-- Medicine Selection -->
                 <div class="col-span-2 md:col-span-1">
                     <label class="block text-sm text-gray-700">Medicine</label>
                    <select name="medicine_id" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                         @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" {{ old('medicine_id', $order->medicine_id) == $medicine->id ? 'selected' : '' }}>
                                {{ $medicine->name }} ({{ $medicine->pack_duration_days }} days/pack)
                            </option>
                        @endforeach
                    </select>
                    @error('medicine_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Order Details -->
                <div>
                     <label class="block text-sm text-gray-700">Quantity (Packs)</label>
                    <input type="number" name="packs_ordered" value="{{ old('packs_ordered', $order->packs_ordered) }}" min="1" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('packs_ordered') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                     <label class="block text-sm text-gray-700">Treatment Start Date</label>
                    <input type="date" name="treatment_start_date" value="{{ old('treatment_start_date', $order->treatment_start_date->format('Y-m-d')) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('treatment_start_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Status</label>
                    <select name="status" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="active" {{ old('status', $order->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('notes', $order->notes) }}</textarea>
                    @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Update Order</button>
            </div>
        </form>
    </div>
</x-app-layout>
