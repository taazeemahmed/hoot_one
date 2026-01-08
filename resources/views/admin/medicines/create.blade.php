<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Add Medicine</h3>
        <p class="mt-1 text-sm text-gray-500">Create a new medicine profile</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.medicines.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Medicine Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50" placeholder="e.g. HOO-IMM PLUS B">
                    @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Medicine Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50" placeholder="e.g. HIP-B">
                    @error('code') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Pack Duration (Days)</label>
                    <input type="number" name="pack_duration_days" value="{{ old('pack_duration_days', 30) }}" required min="1" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <span class="text-xs text-gray-500">Used to calculate renewal dates</span>
                    @error('pack_duration_days') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Price (Optional)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('price') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Status</label>
                    <select name="status" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('admin.medicines.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Create Medicine</button>
            </div>
        </form>
    </div>
</x-app-layout>
