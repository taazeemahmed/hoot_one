<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Marketing Members</h3>
            <p class="mt-1 text-sm text-gray-500">Manage marketing team access</p>
        </div>
        <a href="{{ route('admin.marketing-members.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
            Add New Member
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-orange-700 bg-orange-100 rounded-lg dark:bg-orange-200 dark:text-orange-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Joined</th>
                        <th class="px-4 py-3">Current Target</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($members as $member)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $member->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $member->email }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $member->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $target = $member->marketingTargets->first();
                            @endphp
                            @if($target)
                                <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full">
                                    {{ $target->leads_assigned_target }} / month
                                </span>
                            @else
                                <span class="text-gray-400">Not set</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('admin.marketing-members.set-target', $member) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg focus:outline-none focus:shadow-outline-gray" title="Set Target">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.marketing-members.edit', $member) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.marketing-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
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
                        <td colspan="4" class="px-4 py-3 text-sm text-center text-gray-500">
                            No marketing members found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 text-gray-500">
            {{ $members->links() }}
        </div>
    </div>
</x-admin-layout>
