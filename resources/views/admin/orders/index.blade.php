<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Orders</h3>
            <p class="mt-1 text-sm text-gray-500">Manage medicine orders and renewals</p>
        </div>
        <a href="{{ route('admin.orders.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
            Create Order
        </a>
    </div>

    <!-- Filters -->
    <div class="p-6 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.orders.index') }}" method="GET">
            
            <!-- Top Row: Search & Actions -->
            <div class="flex flex-col md:flex-row gap-4 mb-6 items-center">
                <div class="flex-1 w-full">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by patient, phone..." class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50 transition-colors">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-hoot-dark text-white font-medium rounded-lg hover:bg-hoot-green focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hoot-green transition-colors shadow-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    
                    <button type="submit" name="export" value="true" class="flex-1 md:flex-none px-6 py-2 bg-white text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hoot-green transition-colors shadow-sm">
                        <svg class="w-4 h-4 inline-block mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export CSV
                    </button>
                    
                     @if(request()->anyFilled(['search', 'representative_id', 'country', 'medicine_id', 'status', 'renewal_filter']))
                        <a href="{{ route('admin.orders.index') }}" class="flex-1 md:flex-none px-4 py-2 bg-gray-100 text-gray-600 font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                            Clear
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filter Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                   <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Representative</label>
                   <select name="representative_id" class="w-full border-gray-300 rounded-lg text-sm focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50 h-10">
                        <option value="">All Representatives</option>
                        @foreach($representatives as $rep)
                            <option value="{{ $rep->id }}" {{ request('representative_id') == $rep->id ? 'selected' : '' }}>{{ $rep->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Country</label>
                    <select name="country" class="w-full border-gray-300 rounded-lg text-sm focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50 h-10">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Medicine</label>
                    <select name="medicine_id" class="w-full border-gray-300 rounded-lg text-sm focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50 h-10">
                        <option value="">All Medicines</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" {{ request('medicine_id') == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50 h-10">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                 <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Renewal</label>
                    <select name="renewal_filter" class="w-full border-gray-300 rounded-lg text-sm focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50 h-10">
                        <option value="">Any Date</option>
                        <option value="overdue" {{ request('renewal_filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="week" {{ request('renewal_filter') == 'week' ? 'selected' : '' }}>Next 7 Days</option>
                        <option value="month" {{ request('renewal_filter') == 'month' ? 'selected' : '' }}>Next 30 Days</option>
                    </select>
                </div>
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
                        <th class="px-4 py-3">Medicine/Packs</th>
                        <th class="px-4 py-3">Start Date</th>
                        <th class="px-4 py-3">Renewal Date</th>
                        <th class="px-4 py-3">Status</th>
                         <th class="px-4 py-3">Rep / Country</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($orders as $order)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $order->patient->name }}</p>
                            <p class="text-xs text-gray-600">{{ $order->patient->phone }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <p>{{ $order->medicine->name }}</p>
                            <p class="text-xs text-gray-600">{{ $order->packs_ordered }} packs</p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $order->treatment_start_date->format('d M Y') }}
                        </td>
                         <td class="px-4 py-3 text-sm">
                            {{ $order->expected_renewal_date->format('d M Y') }}
                            @if($order->status === 'active')
                                <br>
                                <span class="text-xs {{ $order->days_until_renewal <= 7 ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                    ({{ $order->days_until_renewal }} days)
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="px-2 py-1 font-semibold leading-tight {{ $order->status == 'active' ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100' }} rounded-full">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                             <div class="flex flex-col">
                                 <span class="font-semibold text-gray-700">
                                    {{ $order->representative->user->name }}
                                 </span>
                                 <span class="text-gray-500">{{ $order->representative->country }}</span>
                             </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('admin.orders.edit', $order) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-600 rounded-lg focus:outline-none focus:shadow-outline-gray hover:text-gray-900" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                        <td colspan="7" class="px-4 py-3 text-sm text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 text-gray-500">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
