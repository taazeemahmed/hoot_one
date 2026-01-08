<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">WhatsApp Logs</h3>
        <p class="mt-1 text-sm text-gray-500">History of automated messages sent via AISENSY</p>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($logs as $log)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            {{ $log->sent_at ? $log->sent_at->format('d M Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">
                            {{ $log->patient->name }}
                        </td>
                         <td class="px-4 py-3 text-sm">
                            {{ $log->phone_number }}
                        </td>
                         <td class="px-4 py-3 text-sm">
                             @if($log->order)
                                <a href="{{ route('admin.orders.edit', $log->order_id) }}" class="text-hoot-green hover:underline">#{{ $log->order_id }}</a>
                             @else
                                <span class="text-gray-400">-</span>
                             @endif
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $log->status == 'sent' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                         <td class="px-4 py-3 text-xs text-gray-500 max-w-xs truncate">
                            {{ $log->message_body }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-sm text-center text-gray-500">
                            No logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 text-gray-500">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
