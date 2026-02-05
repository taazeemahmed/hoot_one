<x-app-layout>
    {{-- Mobile-First Create Order - Clean, Calm, Professional --}}
    <div x-data="orderForm()" x-init="init()" class="min-h-screen pb-32 lg:pb-8">
        
        {{-- Compact Header --}}
        <div class="mb-4 sm:mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('representative.orders.index') }}" 
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">New Order</h1>
                    <p class="text-sm text-gray-500 hidden sm:block">Record a medicine order for your patient</p>
                </div>
            </div>
        </div>

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('representative.orders.store') }}" method="POST" @submit="handleSubmit">
            @csrf
            
            <div class="space-y-4 sm:space-y-6">
                
                {{-- ═══════════════════════════════════════════════════════════════
                     SECTION 1: PATIENT SELECTION
                ═══════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">1</span>
                                <h2 class="font-semibold text-gray-800">Patient</h2>
                            </div>
                            <template x-if="selectedId || createNew">
                                <span class="text-emerald-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            </template>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6">
                        {{-- Search Existing Patient --}}
                        <div x-show="!createNew" class="space-y-4">
                            <input type="hidden" name="patient_id" x-model="selectedId">
                            
                            {{-- Selected Patient Card --}}
                            <div x-show="selectedId" x-transition class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" x-text="selectedName"></p>
                                            <p class="text-sm text-gray-500" x-text="selectedPhone"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="clearPatient()" 
                                            class="text-sm text-emerald-700 hover:text-emerald-900 font-medium px-3 py-1.5 hover:bg-emerald-100 rounded-lg transition-colors">
                                        Change
                                    </button>
                                </div>
                            </div>

                            {{-- Search Input --}}
                            <div x-show="!selectedId" class="relative">
                                <div class="relative">
                                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" 
                                           x-model="search"
                                           @focus="showDropdown = true"
                                           @input="showDropdown = true"
                                           placeholder="Search patient by name or phone..."
                                           class="block w-full pl-12 pr-4 py-4 text-base border border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                                           autocomplete="off">
                                </div>

                                {{-- Dropdown Results --}}
                                <div x-show="showDropdown && (filteredPatients.length > 0 || search.length > 1)" 
                                     @click.away="showDropdown = false"
                                     class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl max-h-64 overflow-y-auto"
                                     x-cloak>
                                    <template x-for="patient in filteredPatients" :key="patient.id">
                                        <button type="button"
                                                @click="selectPatient(patient)" 
                                                class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition-colors text-left">
                                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-medium text-gray-600" x-text="patient.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-900 truncate" x-text="patient.name"></p>
                                                <p class="text-sm text-gray-500" x-text="patient.phone"></p>
                                            </div>
                                        </button>
                                    </template>
                                    <div x-show="filteredPatients.length === 0 && search.length > 1" 
                                         class="p-4 text-center text-gray-500">
                                        <p class="mb-2">No patients found</p>
                                        <button type="button" @click="startNewPatient()" 
                                                class="text-emerald-600 font-medium hover:text-emerald-700">
                                            + Create new patient
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Quick Add Button --}}
                            <div x-show="!selectedId" class="pt-2">
                                <button type="button" @click="startNewPatient()" 
                                        class="w-full py-3 px-4 border-2 border-dashed border-gray-200 rounded-xl text-gray-600 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50/50 transition-all flex items-center justify-center gap-2 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Add New Patient
                                </button>
                            </div>
                        </div>

                        {{-- New Patient Inline Form --}}
                        <div x-show="createNew" x-transition class="space-y-4">
                            <input type="hidden" name="create_new_patient" :value="createNew ? '1' : '0'">
                            
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="font-medium text-gray-700">New Patient</span>
                                <button type="button" @click="cancelNewPatient()" 
                                        class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                                    Cancel
                                </button>
                            </div>

                            <div class="space-y-4">
                                {{-- Name --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Patient Name</label>
                                    <input type="text" name="new_patient_name" x-model="newPatient.name"
                                           value="{{ old('new_patient_name') }}" 
                                           placeholder="Enter full name"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                                    <div class="flex gap-2">
                                        <select name="new_patient_country_code" 
                                                class="w-24 sm:w-28 px-3 py-3 border border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 bg-gray-50 text-sm">
                                            @foreach($countryCodes as $code)
                                                <option value="{{ $code['code'] }}" {{ $selectedCountryCode == $code['code'] ? 'selected' : '' }}>{{ $code['label'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="tel" name="new_patient_phone" x-model="newPatient.phone"
                                               value="{{ old('new_patient_phone') }}" 
                                               placeholder="Mobile number"
                                               class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                    </div>
                                </div>

                                {{-- Optional Fields (Collapsed) --}}
                                <details class="group">
                                    <summary class="text-sm text-gray-500 cursor-pointer hover:text-gray-700 flex items-center gap-1">
                                        <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        More details (optional)
                                    </summary>
                                    <div class="mt-4 space-y-4 pl-5">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                            <input type="email" name="new_patient_email" 
                                                   value="{{ old('new_patient_email') }}" 
                                                   placeholder="email@example.com"
                                                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                                            <input type="text" name="new_patient_country" 
                                                   value="{{ old('new_patient_country', $representative->country ?? 'India') }}" 
                                                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                        </div>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════════════
                     SECTION 2: ORDER DETAILS
                ═══════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                                <h2 class="font-semibold text-gray-800">Order Details</h2>
                            </div>
                            <template x-if="medicine && packs > 0">
                                <span class="text-emerald-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            </template>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-5">
                        {{-- Medicine Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medicine</label>
                            <div class="grid gap-2">
                                @foreach($medicines as $medicine)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="medicine_id" value="{{ $medicine->id }}" 
                                               x-model="medicine"
                                               @change="medicineDays = {{ $medicine->pack_duration_days }}; calculateRenewal()"
                                               {{ old('medicine_id') == $medicine->id ? 'checked' : '' }}
                                               class="peer sr-only">
                                        <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50/50 hover:border-gray-300 transition-all">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $medicine->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $medicine->pack_duration_days }} days per pack</p>
                                                </div>
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Packs Stepper --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Packs</label>
                            <div class="flex items-center gap-4">
                                <button type="button" @click="packs = Math.max(1, packs - 1); calculateRenewal()"
                                        class="w-14 h-14 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-2xl font-medium text-gray-600 transition-colors active:scale-95">
                                    −
                                </button>
                                <input type="number" name="packs_ordered" x-model="packs" 
                                       @change="calculateRenewal()"
                                       min="1" required 
                                       class="flex-1 text-center py-3 text-2xl font-semibold border-0 bg-transparent focus:ring-0"
                                       readonly>
                                <button type="button" @click="packs++; calculateRenewal()"
                                        class="w-14 h-14 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-2xl font-medium text-gray-600 transition-colors active:scale-95">
                                    +
                                </button>
                            </div>
                            <p x-show="medicine && medicineDays" class="mt-2 text-center text-sm text-gray-500">
                                Total: <span class="font-medium" x-text="packs * medicineDays"></span> days of treatment
                            </p>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Start Date</label>
                            <input type="date" name="treatment_start_date" x-model="startDate"
                                   @change="calculateRenewal()"
                                   value="{{ old('treatment_start_date', date('Y-m-d')) }}" 
                                   required 
                                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>

                        {{-- Notes (Collapsed) --}}
                        <details class="group">
                            <summary class="text-sm text-gray-500 cursor-pointer hover:text-gray-700 flex items-center gap-1">
                                <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Add note (optional)
                            </summary>
                            <div class="mt-3 pl-5">
                                <textarea name="notes" rows="2" 
                                          placeholder="Any special instructions..."
                                          class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none">{{ old('notes') }}</textarea>
                            </div>
                        </details>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════════════
                     SECTION 3: ORDER SUMMARY (Shows when ready)
                ═══════════════════════════════════════════════════════════════ --}}
                <div x-show="isReady" x-transition class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-200 bg-white/50">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-sm font-bold">3</span>
                            <h2 class="font-semibold text-gray-800">Order Summary</h2>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Patient</span>
                                <span class="font-medium text-gray-900" x-text="createNew ? newPatient.name : selectedName"></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Medicine</span>
                                <span class="font-medium text-gray-900" x-text="medicineName"></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Packs</span>
                                <span class="font-medium text-gray-900" x-text="packs"></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Treatment Duration</span>
                                <span class="font-medium text-gray-900" x-text="(packs * medicineDays) + ' days'"></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Expected Renewal</span>
                                <span class="font-semibold text-emerald-600" x-text="renewalDate"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════════
                 STICKY BOTTOM BAR (Mobile)
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 lg:hidden shadow-lg z-30">
                <div class="flex items-center gap-3 max-w-lg mx-auto">
                    <a href="{{ route('representative.orders.index') }}" 
                       class="px-4 py-3 text-gray-600 font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            :disabled="!isReady || isSubmitting"
                            :class="isReady ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                            class="flex-1 py-3.5 font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                        <span x-show="!isSubmitting">Create Order</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>

            {{-- Desktop Submit --}}
            <div class="hidden lg:flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('representative.orders.index') }}" 
                   class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium rounded-xl hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        :disabled="!isReady || isSubmitting"
                        :class="isReady ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-200' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        class="px-8 py-3.5 font-semibold rounded-xl transition-all flex items-center gap-2">
                    <span x-show="!isSubmitting">Create Order</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>

        </form>
    </div>

    <script>
        function orderForm() {
            return {
                // Patient Selection
                createNew: @json(old('create_new_patient') ? true : false),
                search: '',
                selectedId: @json(old('patient_id') ?? ''),
                selectedName: '',
                selectedPhone: '',
                patients: @json($patientsForJs ?? []),
                showDropdown: false,
                newPatient: {
                    name: @json(old('new_patient_name') ?? ''),
                    phone: @json(old('new_patient_phone') ?? '')
                },
                
                // Order Details
                medicine: @json(old('medicine_id') ?? ''),
                medicineName: '',
                medicineDays: 0,
                packs: @json(old('packs_ordered', 1)),
                startDate: @json(old('treatment_start_date', date('Y-m-d'))),
                renewalDate: '',
                
                // UI State
                isSubmitting: false,
                
                // Medicine lookup data
                medicineData: @json($medicines->pluck('pack_duration_days', 'id')),
                medicineNames: @json($medicines->pluck('name', 'id')),
                
                init() {
                    // Restore selected patient on page reload (validation error)
                    if (this.selectedId) {
                        const patient = this.patients.find(p => p.id == this.selectedId);
                        if (patient) {
                            this.selectedName = patient.name;
                            this.selectedPhone = patient.phone;
                        }
                    }
                    
                    // Set medicine info if already selected
                    if (this.medicine) {
                        this.medicineDays = this.medicineData[this.medicine] || 0;
                        this.medicineName = this.medicineNames[this.medicine] || '';
                        this.calculateRenewal();
                    }
                },
                
                get filteredPatients() {
                    if (this.search === '') {
                        return this.patients.slice(0, 10);
                    }
                    const q = this.search.toLowerCase();
                    return this.patients.filter(p => 
                        p.name.toLowerCase().includes(q) || 
                        p.phone.includes(q)
                    ).slice(0, 10);
                },
                
                selectPatient(patient) {
                    this.selectedId = patient.id;
                    this.selectedName = patient.name;
                    this.selectedPhone = patient.phone;
                    this.search = '';
                    this.showDropdown = false;
                },
                
                clearPatient() {
                    this.selectedId = '';
                    this.selectedName = '';
                    this.selectedPhone = '';
                    this.search = '';
                },
                
                startNewPatient() {
                    this.createNew = true;
                    this.clearPatient();
                    this.showDropdown = false;
                },
                
                cancelNewPatient() {
                    this.createNew = false;
                    this.newPatient = { name: '', phone: '' };
                },
                
                calculateRenewal() {
                    if (!this.startDate || !this.medicineDays || !this.packs) return;
                    
                    this.medicineName = this.medicineNames[this.medicine] || '';
                    
                    const start = new Date(this.startDate);
                    const days = this.packs * this.medicineDays;
                    const renewal = new Date(start.getTime() + days * 24 * 60 * 60 * 1000);
                    
                    this.renewalDate = renewal.toLocaleDateString('en-IN', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                },
                
                get isReady() {
                    const hasPatient = this.selectedId || (this.createNew && this.newPatient.name && this.newPatient.phone);
                    const hasOrder = this.medicine && this.packs > 0 && this.startDate;
                    return hasPatient && hasOrder;
                },
                
                handleSubmit(e) {
                    if (!this.isReady) {
                        e.preventDefault();
                        return false;
                    }
                    this.isSubmitting = true;
                }
            }
        }
    </script>
</x-app-layout>
