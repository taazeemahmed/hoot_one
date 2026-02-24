<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Patient Details</h3>
        <p class="mt-1 text-sm text-gray-500">View patient information and history</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Patient Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="inline-block p-4 rounded-full bg-hoot-light text-hoot-dark mb-4">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $patient->name }}</h2>
                        <p class="text-gray-500">{{ $patient->country }}</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Representative</label>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->representative->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Phone</label>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->phone }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Address</label>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Notes</label>
                            <p class="text-sm font-medium text-gray-800 whitespace-pre-line">{{ $patient->notes ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('admin.patients.edit', $patient) }}" class="block w-full py-2 px-4 text-center bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h4 class="font-semibold text-gray-700">Order History</h4>
                    <a href="{{ route('admin.orders.create', ['patient_id' => $patient->id]) }}" class="text-sm text-hoot-green hover:underline font-medium">Create New Order</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Medicine</th>
                                <th class="px-6 py-3">Start Date</th>
                                <th class="px-6 py-3">Packs</th>
                                <th class="px-6 py-3">Renewal Date</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patient->orders->sortByDesc('treatment_start_date') as $order)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $order->medicine->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->treatment_start_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->packs_ordered }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->expected_renewal_date->format('d M Y') }}
                                    @if($order->status === 'active')
                                        <br>
                                        <span class="text-xs {{ $order->days_until_renewal <= 7 ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                            ({{ $order->days_until_renewal }} days)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 font-semibold leading-tight {{ $order->status == 'active' ? 'text-orange-700 bg-orange-100' : 'text-gray-700 bg-gray-100' }} rounded-full text-xs">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
