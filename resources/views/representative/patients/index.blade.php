<x-app-layout>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h3 class="text-2xl sm:text-3xl font-medium text-gray-700">My Patients / Leads</h3>
            <p class="mt-1 text-sm text-gray-500">Manage your patients and follow up on leads</p>
        </div>
        <a href="{{ route('representative.patients.create') }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
            Add New Patient
        </a>
    </div>

    <!-- Filters -->
    <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
        <form action="{{ route('representative.patients.index') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." class="w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg md:w-auto active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile Cards -->
    <div class="space-y-4 sm:hidden">
        @forelse($patients as $patient)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $patient->name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                    </div>
                    <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                        {{ $patient->lead_status === 'assigned' ? 'text-purple-700 bg-purple-100' : '' }}
                        {{ $patient->lead_status === 'converted' ? 'text-green-700 bg-green-100' : '' }}
                        {{ $patient->lead_status === 'follow_up' ? 'text-orange-700 bg-orange-100' : '' }}
                        {{ $patient->lead_status === 'not_interested' ? 'text-red-700 bg-red-100' : 'text-gray-700 bg-gray-100' }}">
                        {{ ucfirst(str_replace('_', ' ', $patient->lead_status)) }}
                    </span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <a href="tel:{{ $patient->phone }}" onclick="openLogModal({{ $patient->id }}, '{{ $patient->name }}')" class="text-indigo-600 font-bold text-sm">{{ $patient->phone }}</a>
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-hoot-green rounded-full">
                        {{ $patient->orders_count }}
                    </span>
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <button onclick="openLogModal({{ $patient->id }}, '{{ $patient->name }}')" class="text-blue-600 text-xs border border-blue-600 px-3 py-1.5 rounded">Log Call</button>
                    <a href="{{ route('representative.patients.show', $patient) }}" class="text-gray-600 text-xs border border-gray-200 px-3 py-1.5 rounded">View</a>
                    <a href="{{ route('representative.patients.edit', $patient) }}" class="text-gray-600 text-xs border border-gray-200 px-3 py-1.5 rounded">Edit</a>
                </div>
            </div>
        @empty
            <div class="text-sm text-center text-gray-500 bg-white rounded-lg p-6">
                No patients found.
            </div>
        @endforelse
        <div class="px-2">
            {{ $patients->links() }}
        </div>
    </div>

    <!-- Table (Desktop) -->
    <div class="hidden sm:block w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Patient / Status</th>
                        <th class="px-4 py-3">Contact</th>
                        <th class="px-4 py-3">Quality</th>
                        <th class="px-4 py-3">Orders</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($patients as $patient)
                    <tr class="text-gray-700 {{ $patient->lead_status === 'assigned' ? 'bg-purple-50' : '' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $patient->name }}</p>
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                        {{ $patient->lead_status === 'assigned' ? 'text-purple-700 bg-purple-100' : '' }}
                                        {{ $patient->lead_status === 'converted' ? 'text-green-700 bg-green-100' : '' }}
                                        {{ $patient->lead_status === 'follow_up' ? 'text-orange-700 bg-orange-100' : '' }}
                                        {{ $patient->lead_status === 'not_interested' ? 'text-red-700 bg-red-100' : 'text-gray-700 bg-gray-100' }}
                                        text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $patient->lead_status)) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="tel:{{ $patient->phone }}" onclick="openLogModal({{ $patient->id }}, '{{ $patient->name }}')" class="text-indigo-600 font-bold hover:underline">{{ $patient->phone }}</a>
                            <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($patient->lead_quality)
                                <span class="px-2 py-0.5 rounded text-xs {{ $patient->lead_quality === 'hot' ? 'bg-red-100 text-red-800' : 'bg-gray-100' }}">
                                    {{ ucfirst($patient->lead_quality) }}
                                </span>
                            @endif
                            <div class="text-xs text-gray-400">Src: {{ ucfirst($patient->source) }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-hoot-green rounded-full">
                                {{ $patient->orders_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center flex-wrap gap-2 text-sm">
                                 <button onclick="openLogModal({{ $patient->id }}, '{{ $patient->name }}')" class="text-blue-600 hover:text-blue-900 text-xs border border-blue-600 px-2 py-1 rounded">Log Call</button>
                                 <a href="{{ route('representative.patients.show', $patient) }}" class="text-gray-600 hover:text-gray-900">
                                    View
                                </a>
                                <a href="{{ route('representative.patients.edit', $patient) }}" class="text-gray-600 hover:text-gray-900">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">
                            No patients found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 text-gray-500">
            {{ $patients->links() }}
        </div>
    </div>

    <!-- Log Activity Modal (Shared) -->
    <div id="logModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Log Activity</h3>
                <p class="text-sm text-gray-500 mb-4">Logging interaction for <span id="logLeadName" class="font-bold"></span></p>
                <form id="logForm" method="POST" action="{{ route('lead_activities.store') }}">
                    @csrf
                    <input type="hidden" name="patient_id" id="logPatientId">
                    <input type="hidden" name="type" value="call">
                    
                    <div class="text-left mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Outcome</label>
                        <select name="result" id="logResult" onchange="toggleFollowUp()" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            <option value="info">General Info / Note</option>
                            <option value="follow_up">Follow Up Required</option>
                            <option value="not_reachable">Not Reachable</option>
                            <option value="not_interested">Not Interested</option>
                            <option value="converted">Converted (Ready for Order)</option>
                        </select>
                    </div>

                    <div class="text-left mb-4 hidden" id="followUpContainer">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Follow Up Date</label>
                        <input type="date" name="follow_up_at" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>

                    <div class="text-left mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="document.getElementById('logModal').classList.add('hidden')" class="mr-2 px-4 py-2 bg-gray-300 rounded text-gray-800">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function openLogModal(id, name) {
            document.getElementById('logLeadName').innerText = name;
            document.getElementById('logPatientId').value = id;
            document.getElementById('logModal').classList.remove('hidden');
        }

        function toggleFollowUp() {
            let result = document.getElementById('logResult').value;
            let container = document.getElementById('followUpContainer');
            if (result === 'follow_up') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
