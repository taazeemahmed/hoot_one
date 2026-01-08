<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Edit Representative</h3>
        <p class="mt-1 text-sm text-gray-500">Update sales representative details</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.representatives.update', $representative) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2">
                <!-- Account Details -->
                <div class="col-span-2">
                    <h4 class="text-lg font-semibold text-gray-700">Account Details</h4>
                    <hr class="mt-2 text-gray-200">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $representative->user->name) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $representative->user->email) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Password</label>
                    <input type="password" name="password" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <span class="text-xs text-gray-500">Leave blank to keep current password</span>
                    @error('password') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                </div>

                <!-- Personal Information -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-semibold text-gray-700">Profile Information</h4>
                    <hr class="mt-2 text-gray-200">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', $representative->country) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('country') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Country Code (e.g. +91)</label>
                    <input type="text" name="country_code" value="{{ old('country_code', $representative->country_code) }}" placeholder="+XX" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('country_code') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Region</label>
                    <input type="text" name="region" value="{{ old('region', $representative->region) }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('region') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $representative->phone) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Status</label>
                    <select name="status" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="active" {{ old('status', $representative->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $representative->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Address</label>
                    <textarea name="address" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('address', $representative->address) }}</textarea>
                    @error('address') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('admin.representatives.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Update Representative</button>
            </div>
        </form>
    </div>
</x-app-layout>
