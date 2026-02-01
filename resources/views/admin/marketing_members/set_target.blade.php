<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Set Monthly Target</h3>
        <p class="mt-1 text-sm text-gray-500">Set the lead assignment target for {{ $user->name }} for {{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}</p>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white p-6 max-w-lg">
        <form action="{{ route('admin.marketing-members.store-target', $user) }}" method="POST">
            @csrf
            
            <input type="hidden" name="month_year" value="{{ $currentMonth }}">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Month
                </label>
                <input type="text" value="{{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}" readonly class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight bg-gray-100">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="leads_assigned_target">
                    Target Leads (Assignments)
                </label>
                <input type="number" name="leads_assigned_target" id="leads_assigned_target" value="{{ old('leads_assigned_target', $target->leads_assigned_target) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                <p class="text-xs text-gray-500 mt-1">Number of leads this member should aim to assign/create this month.</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Target
                </button>
                <a href="{{ route('admin.marketing-members.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
