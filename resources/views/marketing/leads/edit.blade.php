<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Lead') }}: {{ $lead->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('marketing.leads.update', $lead) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                Patient Name
                            </label>
                            <input class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1" id="name" type="text" name="name" value="{{ old('name', $lead->name) }}" required />
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700" for="phone">
                                Phone Number
                            </label>
                            <input class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1" id="phone" type="text" name="phone" value="{{ old('phone', $lead->phone) }}" required />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700" for="email">
                                Email (Optional)
                            </label>
                            <input class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1" id="email" type="email" name="email" value="{{ old('email', $lead->email) }}" />
                        </div>

                        <!-- Country -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700" for="country">
                                Country
                            </label>
                            <input class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1" id="country" type="text" name="country" value="{{ old('country', $lead->country) }}" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Lead Quality -->
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700" for="lead_quality">
                                    Quality
                                </label>
                                <select name="lead_quality" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1">
                                    <option value="cold" {{ $lead->lead_quality == 'cold' ? 'selected' : '' }}>Cold</option>
                                    <option value="warm" {{ $lead->lead_quality == 'warm' ? 'selected' : '' }}>Warm</option>
                                    <option value="hot" {{ $lead->lead_quality == 'hot' ? 'selected' : '' }}>Hot</option>
                                    <option value="invalid" {{ $lead->lead_quality == 'invalid' ? 'selected' : '' }}>Invalid</option>
                                </select>
                            </div>

                            <!-- Source -->
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700" for="source">
                                    Source
                                </label>
                                <select name="source" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1">
                                    <option value="call" {{ $lead->source == 'call' ? 'selected' : '' }}>Call</option>
                                    <option value="whatsapp" {{ $lead->source == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="website" {{ $lead->source == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="referral" {{ $lead->source == 'referral' ? 'selected' : '' }}>Referral</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700" for="notes">
                                Notes
                            </label>
                            <textarea class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full mt-1" id="notes" name="notes" rows="5">{{ old('notes', $lead->notes) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Activity History (View Only) -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Activity History</h3>
                <div class="space-y-4">
                    @forelse($lead->activities as $activity)
                        <div class="border-l-4 border-indigo-500 pl-4 py-2">
                            <div class="text-sm font-semibold">{{ ucfirst($activity->type) }} - {{ $activity->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->created_at->format('M d, Y h:i A') }}</div>
                            <div class="text-sm text-gray-700 mt-1">{{ $activity->notes }}</div>
                            @if($activity->result)
                                <div class="text-xs font-bold text-indigo-700 mt-1">Result: {{ ucfirst($activity->result) }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-gray-500">No activity logged yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
