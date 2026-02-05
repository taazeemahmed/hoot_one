<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Edit Patient</h3>
        <p class="mt-1 text-sm text-gray-500">Update patient details</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Representative Selection -->
                <div class="col-span-2">
                     <label class="block text-sm text-gray-700">Assign Representative (Optional)</label>
                    <select name="representative_id" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="">Select Representative</option>
                        @foreach($representatives as $rep)
                            <option value="{{ $rep->id }}" {{ old('representative_id', $patient->representative_id) == $rep->id ? 'selected' : '' }}>{{ $rep->user->name }} ({{ $rep->country }})</option>
                        @endforeach
                    </select>
                    @error('representative_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    <p class="mt-1 text-xs text-gray-500">Leave blank for company direct / super admin orders (international patients).</p>
                </div>

                <!-- Personal Information -->
                <div>
                    <label class="block text-sm text-gray-700">Patient Name</label>
                    <input type="text" name="name" value="{{ old('name', $patient->name) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Email Address (Optional)</label>
                    <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', $patient->country) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('country') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Address</label>
                    <textarea name="address" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('address', $patient->address) }}</textarea>
                    @error('address') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Notes (Medical History/Comments)</label>
                    <textarea name="notes" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('notes', $patient->notes) }}</textarea>
                    @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Update Patient</button>
            </div>
        </form>
    </div>
</x-app-layout>
