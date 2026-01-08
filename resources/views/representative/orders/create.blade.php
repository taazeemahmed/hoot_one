<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Create New Order</h3>
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

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('representative.orders.store') }}" method="POST"
              x-data="{ 
                  createNew: {{ old('create_new_patient') ? 'true' : 'false' }},
                  search: '',
                  selectedId: '{{ old('patient_id') }}',
                  patients: {{ $patients->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'phone' => $p->phone, 'label' => $p->name . ' (' . $p->phone . ')']) }},
                  get filteredPatients() {
                      if (this.search === '') {
                          return this.patients.slice(0, 10); // show first 10
                      }
                      return this.patients.filter(p => p.label.toLowerCase().includes(this.search.toLowerCase()));
                  },
                  selectPatient(id, name) {
                      this.selectedId = id;
                      this.search = name;
                      this.showDropdown = false;
                  },
                  showDropdown: false
              }">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                <!-- Patient Selection with Search -->
                <div class="col-span-2" x-show="!createNew">
                    <label class="block text-sm text-gray-700 mb-1">Select Patient</label>
                    
                    <div class="relative">
                        <input type="hidden" name="patient_id" x-model="selectedId">
                        
                        <input type="text" 
                               x-model="search"
                               @focus="showDropdown = true"
                               @click.away="showDropdown = false"
                               placeholder="Type to search patient by name or phone..."
                               class="block w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50"
                               :required="!createNew">

                        <!-- Dropdown List -->
                        <div x-show="showDropdown && filteredPatients.length > 0" 
                             class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                             style="display: none;">
                            <template x-for="patient in filteredPatients" :key="patient.id">
                                <div @click="selectPatient(patient.id, patient.label)" 
                                     class="px-4 py-2 hover:bg-hoot-light cursor-pointer text-sm text-gray-700">
                                    <span x-text="patient.label"></span>
                                </div>
                            </template>
                        </div>
                         <div x-show="showDropdown && filteredPatients.length === 0" 
                             class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg p-3 text-sm text-gray-500"
                             style="display: none;">
                            No patients found.
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="button" @click="createNew = true; selectedId = ''; search = ''" class="text-sm text-hoot-green hover:underline font-semibold">
                            + Create New Patient
                        </button>
                    </div>
                </div>

                <!-- Instant Patient Creation Form -->
                <div class="col-span-2 p-4 bg-gray-50 rounded-lg border border-gray-200" x-show="createNew" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-gray-700">New Patient Details</h4>
                        <button type="button" @click="createNew = false" class="text-sm text-red-500 hover:text-red-700">Cancel</button>
                    </div>
                    
                    <input type="hidden" name="create_new_patient" :value="createNew ? '1' : '0'">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="new_patient_name" value="{{ old('new_patient_name') }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Phone <span class="text-red-500">*</span></label>
                            <div class="flex mt-1">
                                <select name="new_patient_country_code" class="border-gray-300 rounded-l-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                                    @foreach($countryCodes as $code)
                                        <option value="{{ $code['code'] }}" {{ $selectedCountryCode == $code['code'] ? 'selected' : '' }}>{{ $code['label'] }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="new_patient_phone" value="{{ old('new_patient_phone') }}" placeholder="Mobile Number" class="block w-full border-l-0 border-gray-300 rounded-r-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Email</label>
                            <input type="email" name="new_patient_email" value="{{ old('new_patient_email') }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        </div>
                         <div>
                            <label class="block text-sm text-gray-700">Country</label>
                             <input type="text" name="new_patient_country" value="{{ $representative->country }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="col-span-2 border-t pt-4">
                    <h4 class="font-semibold text-gray-700 mb-4">Order Details</h4>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Medicine</label>
                    <select name="medicine_id" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                        <option value="">Select Medicine</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                {{ $medicine->name }} ({{ $medicine->pack_duration_days }} days/pack) - ${{ number_format($medicine->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Packs Ordered (Qty)</label>
                    <input type="number" name="packs_ordered" value="{{ old('packs_ordered', 1) }}" min="1" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Treatment Start Date</label>
                    <input type="date" name="treatment_start_date" value="{{ old('treatment_start_date', date('Y-m-d')) }}" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">{{ old('notes') }}</textarea>
                </div>

            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Save Order</button>
                <a href="{{ route('representative.orders.index') }}" class="ml-2 px-4 py-2 text-sm text-gray-700 rounded-lg bg-gray-200 hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
