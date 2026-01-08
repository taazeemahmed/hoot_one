<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Create Order</h3>
        <p class="mt-1 text-sm text-gray-500">Record a new medicine sale</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                 <!-- Patient Selection -->
                <div class="col-span-2 md:col-span-1">
                     <label class="block text-sm text-gray-700">Select Patient</label>
                    <select name="patient_id" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ (old('patient_id') == $patient->id || (isset($selectedPatient) && $selectedPatient->id == $patient->id)) ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->representative->user->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    <p class="mt-1 text-xs text-gray-500">Don't see the patient? <a href="{{ route('admin.patients.create') }}" class="text-hoot-green hover:underline">Add new patient</a></p>
                </div>

                <!-- Medicine Selection -->
                 <div class="col-span-2 md:col-span-1">
                     <label class="block text-sm text-gray-700">Select Medicine</label>
                    <select name="medicine_id" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="">Select Medicine</option>
                         @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                {{ $medicine->name }} ({{ $medicine->pack_duration_days }} days/pack)
                            </option>
                        @endforeach
                    </select>
                    @error('medicine_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Order Details -->
                <div>
                     <label class="block text-sm text-gray-700">Quantity (Packs)</label>
                    <input type="number" name="packs_ordered" value="{{ old('packs_ordered', 1) }}" min="1" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    @error('packs_ordered') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                     <label class="block text-sm text-gray-700">Treatment Start Date</label>
                    <input type="date" name="treatment_start_date" value="{{ old('treatment_start_date', date('Y-m-d')) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <p class="mt-1 text-xs text-gray-500">Renewal date will be calculated automatically.</p>
                    @error('treatment_start_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Create Order</button>
            </div>
        </form>
    </div>
</x-app-layout>
