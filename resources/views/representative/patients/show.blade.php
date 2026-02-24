<x-app-layout>
    <div class="space-y-4">
        <!-- Page Header -->
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('representative.patients.index') }}" class="p-2 text-corp-400 hover:text-corp-700 hover:bg-corp-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-corp-900">{{ $patient->name }}</h1>
                <p class="text-sm text-corp-400">Patient profile & order history</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <!-- Patient Info Card -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-xl border border-corp-100 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-br from-corp-900 to-hoot-dark p-6 text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-white">{{ $patient->name }}</h2>
                        <p class="text-white/70 text-sm">{{ $patient->country ?? 'N/A' }}</p>
                    </div>

                    <div class="p-4 space-y-4">
                        <!-- Phone with copy -->
                        <div>
                            <label class="text-xs font-semibold text-corp-400 uppercase tracking-wide">Phone</label>
                            <div class="flex items-center gap-2 mt-1 bg-corp-50 rounded-lg px-3 py-2">
                                <svg class="w-4 h-4 text-corp-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span class="text-sm font-mono text-corp-700 flex-1">{{ $patient->phone }}</span>
                                <button onclick="navigator.clipboard.writeText('{{ $patient->phone }}').then(()=>{this.innerHTML='<svg class=\'w-4 h-4 text-orange-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>';setTimeout(()=>{this.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'/></svg>'},1500)})"
                                        class="p-1.5 text-corp-400 hover:text-corp-700 hover:bg-corp-100 rounded-md transition-colors" title="Copy phone number">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-corp-400 uppercase tracking-wide">Email</label>
                            <p class="text-sm text-corp-700 mt-1">{{ $patient->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-corp-400 uppercase tracking-wide">Address</label>
                            <p class="text-sm text-corp-700 mt-1">{{ $patient->address ?? 'N/A' }}</p>
                        </div>
                        @if($patient->notes)
                        <div>
                            <label class="text-xs font-semibold text-corp-400 uppercase tracking-wide">Notes</label>
                            <p class="text-sm text-corp-700 mt-1 whitespace-pre-line bg-corp-50 rounded-lg p-3">{{ $patient->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-corp-100 p-4 shadow-sm space-y-2">
                    <h4 class="text-sm font-semibold text-corp-900 mb-3">Quick Actions</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $patient->phone) }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            WhatsApp
                        </a>
                        <a href="tel:{{ $patient->phone }}"
                           class="inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-corp-600 hover:bg-corp-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Call
                        </a>
                    </div>
                    <a href="{{ route('representative.patients.edit', $patient) }}" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-corp-50 hover:bg-corp-100 text-corp-600 border border-corp-200 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Orders History -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-corp-100 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-corp-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-corp-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <h4 class="font-semibold text-corp-900">Order History</h4>
                        </div>
                        <a href="{{ route('representative.orders.create', ['patient_id' => $patient->id]) }}" class="inline-flex items-center gap-1.5 text-sm text-white bg-corp-900 hover:bg-corp-800 px-4 py-2 rounded-xl font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            New Order
                        </a>
                    </div>
                    <div class="p-5">
                        <div class="relative pl-8 border-l-2 border-corp-200 space-y-6">
                            @forelse($patient->orders->sortByDesc('treatment_start_date') as $order)
                            <div class="relative">
                                <!-- Timeline Dot -->
                                <div class="absolute -left-[25px] mt-1.5 w-4 h-4 rounded-full border-2 border-white shadow-sm {{ $order->status == 'active' ? 'bg-orange-500' : 'bg-corp-300' }}"></div>

                                <!-- Content Card -->
                                <div class="p-4 bg-corp-50 rounded-xl border border-corp-100 hover:shadow-sm transition-shadow">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h5 class="text-sm font-bold text-corp-900">{{ $order->medicine->name }}</h5>
                                            <span class="text-xs text-corp-400 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $order->treatment_start_date->format('d M Y') }}
                                            </span>
                                        </div>
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $order->status == 'active' ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-corp-100 text-corp-500 border-corp-200' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-corp-500">
                                        <span class="font-medium text-corp-600">Quantity:</span> {{ $order->packs_ordered }} packs
                                    </p>

                                    @if($order->status == 'active')
                                    <div class="mt-3 text-xs p-3 rounded-lg bg-white border border-corp-100">
                                        <p class="font-semibold text-corp-700 mb-1">Next Renewal</p>
                                        <div class="flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1.5 {{ $order->days_until_renewal <= 7 ? 'text-red-500' : 'text-corp-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="{{ $order->days_until_renewal <= 7 ? 'text-red-600 font-bold' : 'text-corp-600' }}">
                                                {{ $order->expected_renewal_date->format('d M Y') }}
                                                ({{ $order->days_until_renewal }} days)
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-corp-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-corp-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm text-corp-400">No order history available.</p>
                                <a href="{{ route('representative.orders.create', ['patient_id' => $patient->id]) }}" class="inline-flex items-center gap-1.5 text-sm text-hoot-green hover:text-hoot-dark font-medium mt-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Create first order
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
