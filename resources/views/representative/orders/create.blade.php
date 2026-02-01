<x-app-layout>
    <div class="mb-6">
        <h3 class="text-2xl sm:text-3xl font-medium text-gray-700">Create New Order</h3>
        <p class="mt-1 text-sm text-gray-500">Record a medicine order for a patient.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm sm:shadow-lg overflow-hidden">
        <!-- Step Indicators -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4 sm:p-6 text-white">
            <h4 class="text-lg font-bold mb-4">Quick Order Form</h4>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center font-bold">1</span>
                    <span>Select Patient</span>
                </div>
                <div class="hidden sm:block w-8 h-0.5 bg-white/30"></div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center font-bold">2</span>
                    <span>Choose Medicine</span>
                </div>
                <div class="hidden sm:block w-8 h-0.5 bg-white/30"></div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center font-bold">3</span>
                    <span>Confirm</span>
                </div>
            </div>
        </div>

        <form action="{{ route('representative.orders.store') }}" method="POST" class="p-4 sm:p-6 lg:p-8"
              x-data="orderForm()" x-init="initPatients()">
            @csrf
            
            <div class="space-y-8">
                {{-- STEP 1: Patient Selection --}}
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-100">
                    <h5 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-sm">1</span>
                        Patient Selection
                    </h5>

                    <div x-show="!createNew" class="space-y-4">
                        <div class="relative">
                            <input type="hidden" name="patient_id" x-model="selectedId">
                            
                            <div class="flex items-center gap-2 mb-2">
                                <label class="block text-sm font-medium text-gray-700">Search or Select Patient</label>
                            </div>
                            
                            <input type="text" 
                                   x-model="search"
                                   @focus="showDropdown = true"
                                   @click.away="showDropdown = false"
                                   placeholder="🔍 Type patient name or phone..."
                                class="block w-full px-4 py-3 text-base sm:text-lg border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all"
                                   :required="!createNew">

                            <!-- Selected Patient Display -->
                            <div x-show="selectedId && !showDropdown" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-green-600">✓</span>
                                    <span class="font-medium text-green-800" x-text="search"></span>
                                </div>
                                <button type="button" @click="selectedId = ''; search = ''" class="text-green-600 hover:text-green-800 text-sm">Change</button>
                            </div>

                            <!-- Dropdown List -->
                            <div x-show="showDropdown && filteredPatients.length > 0" 
                                 class="absolute z-10 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto"
                                 x-cloak>
                                <template x-for="patient in filteredPatients" :key="patient.id">
                                    <div @click="selectPatient(patient.id, patient.label)" 
                                         class="px-4 py-3 hover:bg-purple-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors">
                                        <span class="font-medium text-gray-800" x-text="patient.name"></span>
                                        <span class="text-sm text-gray-500 ml-2" x-text="patient.phone"></span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="showDropdown && filteredPatients.length === 0 && search.length > 0" 
                                 class="absolute z-10 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-lg p-4 text-gray-500 text-center"
                                 x-cloak>
                                No patients found matching "<span x-text="search"></span>"
                            </div>
                        </div>

                        <div class="text-center pt-2">
                            <button type="button" @click="createNew = true; selectedId = ''; search = ''" 
                                    class="inline-flex items-center px-4 py-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg font-semibold transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Create New Patient
                            </button>
                        </div>
                    </div>

                    <!-- New Patient Form -->
                    <div x-show="createNew" x-transition class="space-y-4">
                        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
                            <span class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="text-purple-600">👤</span> New Patient Details
                            </span>
                            <button type="button" @click="createNew = false" class="text-sm text-red-500 hover:text-red-700 font-medium">✕ Cancel</button>
                        </div>
                        
                        <input type="hidden" name="create_new_patient" :value="createNew ? '1' : '0'">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="new_patient_name" value="{{ old('new_patient_name') }}" 
                                       class="block w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <select name="new_patient_country_code" class="border-2 border-gray-200 border-r-0 rounded-l-lg focus:border-purple-500 focus:ring focus:ring-purple-200 bg-gray-50 px-3">
                                        @foreach($countryCodes as $code)
                                            <option value="{{ $code['code'] }}" {{ $selectedCountryCode == $code['code'] ? 'selected' : '' }}>{{ $code['label'] }}</option>
                                        @endforeach
                                    </select>
                                     <input type="text" name="new_patient_phone" value="{{ old('new_patient_phone') }}" placeholder="Mobile Number" 
                                         class="block w-full px-4 py-2.5 border-2 border-gray-200 rounded-r-lg focus:border-purple-500 focus:ring focus:ring-purple-200">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                                <input type="email" name="new_patient_email" value="{{ old('new_patient_email') }}" 
                                       class="block w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input type="text" name="new_patient_country" value="{{ $representative->country ?? 'India' }}" 
                                       class="block w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: Order Details --}}
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-100">
                    <h5 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">2</span>
                        Order Details
                    </h5>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medicine <span class="text-red-500">*</span></label>
                                <select name="medicine_id" required 
                                    class="block w-full px-4 py-3 text-base sm:text-lg border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring focus:ring-orange-200 transition-all">
                                <option value="">-- Select Medicine --</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                        {{ $medicine->name }} ({{ $medicine->pack_duration_days }} days/pack)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Packs Ordered <span class="text-red-500">*</span></label>
                            <input type="number" name="packs_ordered" value="{{ old('packs_ordered', 1) }}" min="1" required 
                                class="block w-full px-4 py-3 text-base sm:text-lg border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring focus:ring-orange-200 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="treatment_start_date" value="{{ old('treatment_start_date', date('Y-m-d')) }}" required 
                                class="block w-full px-4 py-3 text-base sm:text-lg border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring focus:ring-orange-200 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea name="notes" rows="2" 
                                      class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring focus:ring-orange-200 transition-all">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('representative.orders.index') }}" 
                       class="px-6 py-3 text-center text-gray-600 hover:text-gray-800 font-medium rounded-xl hover:bg-gray-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 shadow-sm sm:shadow-lg transition-all">
                        💾 Save Order
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function orderForm() {
            return {
                createNew: @json(old('create_new_patient') ? true : false),
                search: '',
                selectedId: @json(old('patient_id') ?? ''),
                patients: @json($patientsForJs ?? []),
                showDropdown: false,
                
                initPatients() {
                    // no-op: patients list is injected server-side
                },
                
                get filteredPatients() {
                    if (this.search === '') {
                        return this.patients.slice(0, 15);
                    }
                    return this.patients.filter(p => p.label.toLowerCase().includes(this.search.toLowerCase()));
                },
                
                selectPatient(id, name) {
                    this.selectedId = id;
                    this.search = name;
                    this.showDropdown = false;
                }
            }
        }
    </script>
</x-app-layout>
