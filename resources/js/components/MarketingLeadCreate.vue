<template>
    <div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8 font-jakarta">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Create New Lead</h1>
                    <p class="text-slate-500 mt-2 font-medium">Add a new prospective conversion to your pipeline.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/marketing/leads" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                        Cancel
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100/60 relative overflow-hidden">
                        <!-- Decorative Header -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            
                            <!-- Basic Info Section -->
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center text-sm">01</span>
                                    Lead Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Patient Name</label>
                                        <input v-model="form.name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none font-medium" placeholder="E.g. John Doe">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Phone Number</label>
                                        <input v-model="form.phone" type="tel" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none font-medium" placeholder="+1234567890">
                                    </div>
                                    <div class="md:col-span-2 space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Email Address (Optional)</label>
                                        <input v-model="form.email" type="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none font-medium" placeholder="john@example.com">
                                    </div>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Assignment Section -->
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center text-sm">02</span>
                                    Assignment & Details
                                </h3>
                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Assign to Representative</label>
                                        <div class="relative">
                                            <select v-model="form.representative_id" @change="updateCountry" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all outline-none font-medium appearance-none cursor-pointer">
                                                <option value="" disabled>Select a Representative...</option>
                                                <option v-for="rep in representatives" :key="rep.id" :value="rep.id" :data-country="rep.country">
                                                    {{ rep.user.name }} - {{ rep.country }}
                                                </option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Initial Quality</label>
                                            <div class="grid grid-cols-3 gap-3">
                                                <button type="button" @click="form.lead_quality = 'hot'" :class="{'ring-2 ring-orange-500 bg-orange-50': form.lead_quality === 'hot', 'bg-slate-50 border-slate-200 hover:bg-slate-100': form.lead_quality !== 'hot'}" class="p-3 rounded-xl border text-center transition-all group">
                                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">🔥</div>
                                                    <div class="text-xs font-bold text-slate-600">Hot</div>
                                                </button>
                                                <button type="button" @click="form.lead_quality = 'warm'" :class="{'ring-2 ring-amber-500 bg-amber-50': form.lead_quality === 'warm', 'bg-slate-50 border-slate-200 hover:bg-slate-100': form.lead_quality !== 'warm'}" class="p-3 rounded-xl border text-center transition-all group">
                                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">☀️</div>
                                                    <div class="text-xs font-bold text-slate-600">Warm</div>
                                                </button>
                                                <button type="button" @click="form.lead_quality = 'cold'" :class="{'ring-2 ring-blue-500 bg-blue-50': form.lead_quality === 'cold', 'bg-slate-50 border-slate-200 hover:bg-slate-100': form.lead_quality !== 'cold'}" class="p-3 rounded-xl border text-center transition-all group">
                                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">❄️</div>
                                                    <div class="text-xs font-bold text-slate-600">Cold</div>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Source</label>
                                            <div class="relative">
                                                <select v-model="form.source" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none font-medium appearance-none cursor-pointer">
                                                    <option value="call">Call</option>
                                                    <option value="whatsapp">WhatsApp</option>
                                                    <option value="website">Website</option>
                                                    <option value="referral">Referral</option>
                                                </select>
                                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Medical Concern</label>
                                        <div class="relative">
                                            <select v-model="form.medical_concern" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none font-medium appearance-none cursor-pointer">
                                                <option value="HIV">HIV</option>
                                                <option value="HBV">HBV</option>
                                                <option value="HSV">HSV</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Additional Notes</label>
                                        <textarea v-model="form.notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none font-medium resize-none" placeholder="Add any extra context here..."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Bar -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                                <button type="submit" :disabled="loading" class="px-8 py-3 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-violet-500/25 transition-all transform active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2">
                                    <svg v-if="loading" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span v-else>Create Lead</span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Live Preview / Tips Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Preview Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/60 sticky top-6">
                        <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Live Preview</h4>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 border-dashed">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ getInitials(form.name) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 leading-tight">{{ form.name || 'Patient Name' }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ form.country || 'Country' }}</p>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                    <span class="text-slate-500">Status</span>
                                    <span class="px-2.5 py-1 rounded-lg bg-pink-100 text-pink-700 text-xs font-bold uppercase"> Assigned</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                    <span class="text-slate-500">Quality</span>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase" :class="qualityClasses">{{ form.lead_quality }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                    <span class="text-slate-500">Concern</span>
                                    <span class="font-medium text-slate-800">{{ form.medical_concern }}</span>
                                </div>
                                 <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                    <span class="text-slate-500">Assignee</span>
                                    <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded text-xs font-bold">{{ form.representative_id ? getRepName(form.representative_id) : '---' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <div class="flex gap-3">
                                <div class="text-blue-500 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-blue-800">Assigning Strategy</p>
                                    <p class="text-xs text-blue-600 mt-1 leading-relaxed">Selecting a representative maps the lead to their country automatically. Hot leads are prioritized in the rep's dashboard.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed, ref } from 'vue';

const props = defineProps({
    representatives: Array
});

const loading = ref(false);

const form = reactive({
    name: '',
    phone: '',
    email: '',
    country: '',
    representative_id: '',
    lead_quality: 'cold',
    source: 'call',
    medical_concern: 'HIV', // Default
    notes: ''
});

const getInitials = (name) => {
    if (!name) return '??';
    return name.substring(0, 2).toUpperCase();
};

const getRepName = (id) => {
    const rep = props.representatives.find(r => r.id === id);
    return rep ? rep.user.name : 'Unknown';
};

const updateCountry = () => {
    const rep = props.representatives.find(r => r.id === form.representative_id);
    if (rep) {
        form.country = rep.country;
    }
};

const qualityClasses = computed(() => {
    switch (form.lead_quality) {
        case 'hot': return 'bg-orange-100 text-orange-700';
        case 'warm': return 'bg-amber-100 text-amber-700';
        default: return 'bg-blue-100 text-blue-700';
    }
});

const submitForm = async () => {
    loading.value = true;
    try {
        // We use standard form submission via XHR or just post to the endpoint since we are in a hybrid app?
        // Actually, for better UX let's use Axios if available, or just standard form POST to utilize the existing controller redirect.
        // But to make it really "Vue-like", let's use Axios and redirect manually.
        
        await axios.post('/marketing/leads', form);
        window.location.href = '/marketing/leads'; // Redirect on success
    } catch (error) {
        console.error(error);
        alert('Failed to create lead. Please check the inputs.');
        loading.value = false;
    }
};
</script>
