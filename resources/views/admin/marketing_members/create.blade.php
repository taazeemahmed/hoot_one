<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Add Marketing Member</h3>
        </div>
    </div>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.marketing-members.store') }}" method="POST">
            @csrf
            
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Name</span>
                        <input name="name" value="{{ old('name') }}" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" placeholder="Jane Doe" required />
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Email</span>
                        <input name="email" type="email" value="{{ old('email') }}" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" placeholder="jane@example.com" required />
                        @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Password</span>
                        <input name="password" type="password" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" required />
                        @error('password') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Confirm Password</span>
                        <input name="password_confirmation" type="password" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" required />
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
                    Create Member
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
