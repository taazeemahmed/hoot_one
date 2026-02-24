<template>
    <div class="min-h-screen bg-[#F8FAFC] p-4 lg:p-8 font-jakarta">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Lead Management</h1>
                <p class="text-slate-500 mt-2 font-medium">Manage, assign, and track all your leads.</p>
            </div>
            <div class="flex items-center gap-3">
                 <a href="/marketing/dashboard" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                    Dashboard
                </a>
                <a href="/marketing/leads/create" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-xl font-bold text-sm hover:from-violet-700 hover:to-indigo-700 transition-all shadow-lg shadow-violet-500/25 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Lead
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
             <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100/60 flex items-center gap-4 group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg font-bold">
                    👥
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">{{ leads.total }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Leads</p>
                </div>
            </div>
             <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100/60 flex items-center gap-4 group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg font-bold">
                    🔥
                </div>
                <div>
                   <!-- Ideally pass hot count too -->
                    <h3 class="text-2xl font-bold text-slate-900">{{ hotCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hot Leads</p>
                </div>
            </div>
             <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100/60 flex items-center gap-4 group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                    🆕
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">{{ newCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">New / Pending</p>
                </div>
            </div>
             <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100/60 flex items-center gap-4 group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-hoot-maroon flex items-center justify-center text-lg font-bold">
                    ✅
                </div>
                <div>
                     <!-- Ideally pass assigned count -->
                    <h3 class="text-2xl font-bold text-slate-900">{{ assignedCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100/60 mb-6 flex flex-wrap gap-2 items-center">
            <span class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Filter:</span>
            <a href="/marketing/leads" :class="{'bg-violet-600 text-white shadow-lg shadow-violet-500/20': !currentStatus && !currentQuality, 'bg-slate-50 text-slate-600 hover:bg-slate-100': currentStatus || currentQuality}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all">All Leads</a>
            <a href="/marketing/leads?status=new" :class="{'bg-blue-500 text-white shadow-lg shadow-blue-500/20': currentStatus === 'new', 'bg-slate-50 text-slate-600 hover:bg-slate-100': currentStatus !== 'new'}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all">New</a>
            <a href="/marketing/leads?status=assigned" :class="{'bg-purple-500 text-white shadow-lg shadow-purple-500/20': currentStatus === 'assigned', 'bg-slate-50 text-slate-600 hover:bg-slate-100': currentStatus !== 'assigned'}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all">Assigned</a>
            <a href="/marketing/leads?quality=hot" :class="{'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg shadow-orange-500/20': currentQuality === 'hot', 'bg-slate-50 text-slate-600 hover:bg-slate-100': currentQuality !== 'hot'}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all">🔥 Hot Leads</a>
             <a href="/marketing/leads?quality=warm" :class="{'bg-amber-500 text-white shadow-lg shadow-amber-500/20': currentQuality === 'warm', 'bg-slate-50 text-slate-600 hover:bg-slate-100': currentQuality !== 'warm'}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all">☀️ Warm</a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/60 overflow-hidden">
             <!-- Header -->
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">All Leads List</h3>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ leads.total }} Total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                             <th class="px-8 py-4">Lead Info</th>
                             <th class="px-6 py-4">Quality / Source</th>
                             <th class="px-6 py-4">Status</th>
                             <th class="px-6 py-4">Assigned To</th>
                             <th class="px-8 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="lead in leads.data" :key="lead.id" class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 align-top">
                                <div class="flex gap-4">
                                     <div class="w-12 h-12 min-w-[3rem] rounded-2xl bg-gradient-to-br from-violet-100 to-fuchsia-100 flex items-center justify-center text-violet-700 font-bold text-sm shadow-sm">
                                        {{ getInitials(lead.name) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-base">{{ lead.name }}</div>
                                        <a :href="`tel:${lead.phone}`" class="text-sm font-medium text-slate-500 hover:text-violet-600 transition-colors block mt-0.5">{{ lead.phone }}</a>
                                        <div class="text-xs text-slate-400 mt-1" v-if="lead.email">{{ lead.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 align-top">
                                <span v-if="lead.lead_quality === 'hot'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700">🔥 Hot</span>
                                <span v-else-if="lead.lead_quality === 'warm'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700">☀️ Warm</span>
                                <span v-else-if="lead.lead_quality === 'cold'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">❄️ Cold</span>
                                <span v-else class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700">👤 Patient</span>
                                <div class="mt-2 text-xs font-medium text-slate-400">via <span class="capitalize text-slate-600">{{ lead.source }}</span></div>
                            </td>
                            <td class="px-6 py-5 align-top">
                                <span v-if="lead.lead_status === 'new'" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> New
                                </span>
                                <span v-else-if="lead.lead_status === 'assigned'" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Assigned
                                </span>
                                 <span v-else class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                    {{ lead.lead_status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 align-top">
                                <div v-if="lead.representative" class="flex gap-3">
                                    <div class="w-10 h-10 min-w-[2.5rem] rounded-full bg-orange-100 flex items-center justify-center text-orange-700 font-bold text-xs">
                                        {{ getInitials(lead.representative.user.name) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 leading-tight">{{ lead.representative.user.name }}</div>
                                        <div class="text-xs font-medium text-orange-600 mt-0.5">{{ lead.representative.country }}</div>
                                    </div>
                                </div>
                                <div v-else class="flex items-center gap-2 text-slate-400 text-sm font-medium italic">
                                     <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">?</span>
                                     Unassigned
                                </div>
                            </td>
                            <td class="px-8 py-5 align-top text-center">
                                <div class="flex items-center justify-center gap-2">
                                     <button @click="openLogModal(lead)" class="px-3 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg text-xs font-bold shadow-md shadow-sky-500/20 transition-all active:scale-95 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Log
                                    </button>
                                    <button @click="confirmDelete(lead)" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold shadow-md shadow-red-500/20 transition-all active:scale-95 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="leads.data.length === 0">
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                No leads found matching criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Placeholders or proper links if accessible -->
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100" v-if="leads.links">
                <!-- Can implement proper pagination using links prop if needed -->
                <!-- For now relying on standard Laravel links generated in Controller potentially, but since props are JSON, we need manual handling or pass HTML links -->
                <!-- Simple solution: Reload page with page query param -->
                <div class="flex justify-center gap-2 text-xs">
                     <template v-for="(link, index) in leads.links" :key="index">
                        <a v-if="link.url" :href="link.url" class="px-3 py-1 rounded-lg border transition-all" :class="{'bg-violet-600 text-white border-violet-600': link.active, 'bg-white text-slate-600 hover:bg-slate-50 border-slate-200': !link.active}" v-html="link.label"></a>
                    </template>
                </div>
            </div>
        </div>

        <!-- Log Activity Modal -->
        <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showLogModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                 <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                             <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">Log Activity</h3>
                                    <p class="text-xs text-slate-500">{{ selectedLead.name }}</p>
                                </div>
                            </div>
                            <button @click="closeLogModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form @submit.prevent="submitLog">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Outcome</label>
                                    <select v-model="logForm.result" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-sky-200 focus:border-sky-500 outline-none transition-all appearance-none">
                                        <option value="info">📋 General Info / Note</option>
                                        <option value="follow_up">📅 Follow Up Required</option>
                                        <option value="not_reachable">📵 Not Reachable</option>
                                        <option value="not_interested">❌ Not Interested</option>
                                        <option value="converted">✅ Converted (Ready)</option>
                                    </select>
                                </div>

                                <div v-if="logForm.result === 'follow_up'">
                                     <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Follow Up Date</label>
                                     <input type="date" v-model="logForm.follow_up_at" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-sky-200 focus:border-sky-500 outline-none transition-all">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Notes</label>
                                    <textarea v-model="logForm.notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-sky-200 focus:border-sky-500 outline-none transition-all resize-none" placeholder="Brief summary..."></textarea>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-8">
                                <button type="button" @click="closeLogModal" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 hover:border-slate-300 transition-all">Cancel</button>
                                <button type="submit" :disabled="logLoading" class="flex-1 px-4 py-3 bg-sky-500 text-white rounded-xl font-bold shadow-lg shadow-sky-500/25 hover:bg-sky-600 transition-all flex justify-center">
                                    <span v-if="!logLoading">Save Activity</span>
                                    <svg v-else class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
        </Transition>

        <!-- Delete Confirmation Modal -->
        <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Lead</h3>
                        <p class="text-slate-500 text-sm mb-6">Are you sure you want to delete <span class="font-semibold text-slate-700">{{ leadToDelete?.name }}</span>? This action cannot be undone.</p>

                        <div class="flex gap-3">
                            <button @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-all">Cancel</button>
                            <button @click="deleteLead" :disabled="deleteLoading" class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/25 hover:bg-red-600 transition-all flex justify-center items-center gap-2">
                                <svg v-if="deleteLoading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>{{ deleteLoading ? 'Deleting...' : 'Delete' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

// Props passed from Blade
const props = defineProps({
    leads: Object,
    representatives: Array,
    currentStatus: String, // e.g., 'new', 'assigned' from query param
    currentQuality: String,
    // Add simple counts if available, otherwise fallback to 0 or calculate from current page (inaccurate but visual enough for now if backend doesn't pass all)
    // Actually we will accept raw counts if passed
    hotCount: { type: Number, default: 0 },
    newCount: { type: Number, default: 0 },
    assignedCount: { type: Number, default: 0 }
});

const showLogModal = ref(false);
const showDeleteModal = ref(false);
const selectedLead = ref({});
const leadToDelete = ref(null);
const logLoading = ref(false);
const deleteLoading = ref(false);
const logForm = reactive({
    result: 'info',
    notes: '',
    follow_up_at: ''
});

const getInitials = (name) => {
    if (!name) return '??';
    return name.substring(0, 2).toUpperCase();
};

const openLogModal = (lead) => {
    selectedLead.value = lead;
    logForm.result = 'info';
    logForm.notes = '';
    logForm.follow_up_at = '';
    showLogModal.value = true;
};

const closeLogModal = () => {
    showLogModal.value = false;
};

const submitLog = async () => {
    logLoading.value = true;
    try {
        await axios.post('/lead-activities', {
            patient_id: selectedLead.value.id,
            type: 'call', // Defaulting to call/log for marketing
            result: logForm.result,
            notes: logForm.notes,
            follow_up_at: logForm.follow_up_at
        });

        // Success feedback? Close modal and maybe show toast
        closeLogModal();
        alert('Activity logged successfully');
        // Ideally reload or update local state?
        // window.location.reload();
    } catch (error) {
        console.error(error);
        alert('Failed to log activity');
    } finally {
        logLoading.value = false;
    }
};

const confirmDelete = (lead) => {
    leadToDelete.value = lead;
    showDeleteModal.value = true;
};

const deleteLead = async () => {
    if (!leadToDelete.value) return;

    deleteLoading.value = true;
    try {
        // Create a form and submit it for proper Laravel DELETE handling
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/marketing/leads/${leadToDelete.value.id}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        console.error(error);
        alert('Failed to delete lead');
        deleteLoading.value = false;
    }
};

</script>
