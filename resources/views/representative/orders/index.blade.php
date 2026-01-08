<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Orders</h3>
            <p class="mt-1 text-sm text-gray-500">Manage your patient orders</p>
        </div>
        <a href="{{ route('representative.orders.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
            Create Order
        </a>
    </div>

    <!-- Filters -->
    <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
        <form action="{{ route('representative.orders.index') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by patient or medicine..." class="w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
            </div>
            <div>
                <select name="status" class="w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
             <div>
                <select name="renewal_filter" class="w-full border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <option value="">Renewal Date</option>
                    <option value="overdue" {{ request('renewal_filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="week" {{ request('renewal_filter') == 'week' ? 'selected' : '' }}>Next 7 Days</option>
                    <option value="month" {{ request('renewal_filter') == 'month' ? 'selected' : '' }}>Next 30 Days</option>
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
                        <th class="px-4 py-3">Medicine/Packs</th>
                        <th class="px-4 py-3">Start Date</th>
                        <th class="px-4 py-3">Renewal Date</th>
                        <th class="px-4 py-3">Status</th>
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
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('representative.orders.edit', $order) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-600 rounded-lg focus:outline-none focus:shadow-outline-gray hover:text-gray-900" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('representative.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                        <td colspan="6" class="px-4 py-3 text-sm text-center text-gray-500">
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
