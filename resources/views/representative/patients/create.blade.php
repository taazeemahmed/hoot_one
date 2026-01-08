<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Add Patient</h3>
        <p class="mt-1 text-sm text-gray-500">Create a new patient record</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('representative.patients.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Personal Information -->
                <div>
                    <label class="block text-sm text-gray-700">Patient Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Email Address (Optional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Phone</label>
                    <div class="flex mt-1">
                        <select name="country_code" class="border-gray-300 rounded-l-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                            <option value="+1">+1 (US)</option>
                            <option value="+91" selected>+91 (IN)</option>
                            <option value="+44">+44 (UK)</option>
                            <option value="+971">+971 (UAE)</option>
                            <!-- Add more as needed -->
                        </select>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Mobile Number" required class="block w-full border-l-0 border-gray-300 rounded-r-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    </div>
                    @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', auth()->user()->representative->country ?? '') }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('country') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Address</label>
                    <textarea name="address" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('address') }}</textarea>
                    @error('address') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Notes (Medical History/Comments)</label>
                    <textarea name="notes" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('representative.patients.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Create Patient</button>
            </div>
        </form>
    </div>
</x-app-layout>
