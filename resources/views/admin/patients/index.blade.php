<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Patients</h3>
            <p class="mt-1 text-sm text-gray-500">Manage all patients across representatives</p>
        </div>
        <a href="{{ route('admin.patients.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
            Add New Patient
        </a>
    </div>

    <!-- Filters -->
    <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.patients.index') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." class="w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
            </div>
            <div class="w-full md:w-1/4">
                <select name="representative_id" class="w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <option value="">All Representatives</option>
                    @foreach($representatives as $rep)
                        <option value="{{ $rep->id }}" {{ request('representative_id') == $rep->id ? 'selected' : '' }}>{{ $rep->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg md:w-auto active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Representative</th>
                        <th class="px-4 py-3">Country</th>
                        <th class="px-4 py-3">Orders</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($patients as $patient)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $patient->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $patient->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 text-xs text-gray-700 bg-orange-50 rounded-full border border-orange-100">
                                {{ $patient->representative->user->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $patient->country }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-hoot-green rounded-full">
                                {{ $patient->orders_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('admin.patients.show', $patient) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-600 rounded-lg focus:outline-none focus:shadow-outline-gray hover:text-gray-900" aria-label="View">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-600 rounded-lg focus:outline-none focus:shadow-outline-gray hover:text-gray-900" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete the patient and all their orders.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg focus:outline-none focus:shadow-outline-red hover:text-red-900" aria-label="Delete">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
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
</x-app-layout>
