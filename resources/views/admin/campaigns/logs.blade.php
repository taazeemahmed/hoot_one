    <x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="inline-flex items-center text-sm text-corp-500 hover:text-corp-700 transition-colors mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Campaign
            </a>
            <h3 class="text-2xl font-bold text-corp-900">Campaign Logs: {{ $campaign->name }}</h3>
            <div class="flex flex-wrap gap-4 mt-2 text-sm">
                <span class="text-corp-400">Sent: {{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y H:i') : 'N/A' }}</span>
                <span class="text-gray-300">|</span>
                <span class="text-corp-600 font-medium">Total: {{ $campaign->total_recipients }}</span>
                <span class="text-green-600 font-medium">Success: {{ $campaign->success_count }}</span>
                <span class="text-red-600 font-medium">Failed: {{ $campaign->failed_count }}</span>
                <span class="text-amber-600 font-medium">Skipped: {{ $campaign->skipped_count }}</span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-gray-900">{{ $campaign->total_recipients }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Total Recipients</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $campaign->success_count }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Delivered</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-red-600">{{ $campaign->failed_count }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Failed</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="text-2xl font-bold text-amber-600">{{ $campaign->skipped_count }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">Skipped (Duplicates)</div>
            </div>
        </div>

        <!-- Detailed Logs Table -->
        <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-corp-100">
                <h4 class="font-semibold text-corp-900">Recipient Details</h4>
                <p class="text-xs text-corp-400 mt-0.5">Failed entries are shown first for easy debugging</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sent At</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Error / Response</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recipients as $recipient)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $recipient->status === 'failed' ? 'bg-red-50/30' : '' }}">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 text-sm">{{ $recipient->patient->name ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 font-mono">{{ $recipient->phone }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $recipientStatusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'success' => 'bg-green-100 text-green-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        'skipped' => 'bg-amber-100 text-amber-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recipientStatusColors[$recipient->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($recipient->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $recipient->sent_at ? $recipient->sent_at->format('H:i:s') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($recipient->error_message)
                                    @php
                                        $debug = is_array($recipient->api_response) ? $recipient->api_response : [];
                                        $httpStatus = $debug['http_status'] ?? null;
                                        $reasonPhrase = $debug['reason_phrase'] ?? null;
                                        $failureType = $debug['type'] ?? 'unknown';
                                        $responseBody = $debug['response_body'] ?? null;
                                        $responseJson = $debug['response_json'] ?? null;
                                        $exceptionClass = $debug['exception_class'] ?? null;
                                        $endpoint = $debug['endpoint'] ?? null;
                                    @endphp
                                    <div x-data="{ open: false }">
                                        <button @click="open = !open" class="text-xs text-red-600 hover:text-red-800 font-medium underline">
                                            <span x-text="open ? 'Hide Details' : 'View Error'"></span>
                                        </button>
                                        <div x-show="open" x-cloak class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg text-xs">
                                            <p class="font-semibold text-red-800 mb-1">Error Message:</p>
                                            <pre class="text-red-700 whitespace-pre-wrap break-all">{{ $recipient->error_message }}</pre>
                                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 text-red-700">
                                                <p><span class="font-semibold">Failure Type:</span> {{ $failureType }}</p>
                                                <p><span class="font-semibold">HTTP Status:</span> {{ $httpStatus ?? 'N/A' }}</p>
                                                <p class="sm:col-span-2"><span class="font-semibold">Reason Phrase:</span> {{ $reasonPhrase ?? 'N/A' }}</p>
                                                <p class="sm:col-span-2"><span class="font-semibold">Exception:</span> {{ $exceptionClass ?? 'N/A' }}</p>
                                                <p class="sm:col-span-2"><span class="font-semibold">Endpoint:</span> <span class="font-mono break-all">{{ $endpoint ?? 'N/A' }}</span></p>
                                            </div>
                                            @if($responseBody)
                                            <p class="font-semibold text-red-800 mt-2 mb-1">Raw Response Body:</p>
                                            <pre class="text-red-700 whitespace-pre-wrap break-all">{{ $responseBody }}</pre>
                                            @endif
                                            @if($responseJson)
                                            <p class="font-semibold text-red-800 mt-2 mb-1">Response JSON:</p>
                                            <pre class="text-red-700 whitespace-pre-wrap break-all">{{ json_encode($responseJson, JSON_PRETTY_PRINT) }}</pre>
                                            @endif
                                            @if($recipient->api_response)
                                            <p class="font-semibold text-red-800 mt-2 mb-1">Stored Debug Payload:</p>
                                            <pre class="text-red-700 whitespace-pre-wrap break-all">{{ json_encode($recipient->api_response, JSON_PRETTY_PRINT) }}</pre>
                                            @endif
                                            <p class="text-red-500 mt-2">Timestamp: {{ $recipient->sent_at ? $recipient->sent_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                @elseif($recipient->status === 'success' && $recipient->api_response)
                                    <div x-data="{ open: false }">
                                        <button @click="open = !open" class="text-xs text-green-600 hover:text-green-800 font-medium underline">
                                            <span x-text="open ? 'Hide' : 'View Response'"></span>
                                        </button>
                                        <div x-show="open" x-cloak class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg text-xs">
                                            <pre class="text-green-700 whitespace-pre-wrap break-all">{{ json_encode($recipient->api_response, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">No log entries</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recipients->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $recipients->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
