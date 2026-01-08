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
                        <a href="{{ route('representative.patients.edit', $patient) }}" class="block w-full py-2 px-4 text-center bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
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
                    <a href="{{ route('representative.orders.create', ['patient_id' => $patient->id]) }}" class="text-sm text-hoot-green hover:underline font-medium">Create New Order</a>
                </div>
                <div class="p-6">
                    <div class="relative pl-8 border-l-2 border-green-200 space-y-8">
                        @forelse($patient->orders->sortByDesc('treatment_start_date') as $order)
                        <div class="relative">
                            <!-- Timeline Dot -->
                            <div class="absolute -left-10 mt-1.5 w-4 h-4 rounded-full border-2 border-white {{ $order->status == 'active' ? 'bg-hoot-green' : 'bg-gray-300' }}"></div>
                            
                            <!-- Content Card -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800">{{ $order->medicine->name }}</h5>
                                        <span class="text-xs text-gray-500">{{ $order->treatment_start_date->format('d M Y') }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $order->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">
                                    <span class="font-medium">Quantity:</span> {{ $order->packs_ordered }} packs
                                </p>
                                
                                @if($order->status == 'active')
                                <div class="mt-2 text-xs p-2 rounded bg-white border border-gray-200">
                                    <p class="font-semibold text-gray-700">Next Renewal:</p>
                                    <div class="flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="{{ $order->days_until_renewal <= 7 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                            {{ $order->expected_renewal_date->format('d M Y') }}
                                            ({{ $order->days_until_renewal }} days)
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-gray-500 italic">No order history available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
