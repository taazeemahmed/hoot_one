<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-3xl font-medium text-gray-700">Edit Marketing Member</h3>
        </div>
    </div>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.marketing-members.update', $marketing_member) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Name</span>
                        <input name="name" value="{{ old('name', $marketing_member->name) }}" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" required />
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Email</span>
                        <input name="email" type="email" value="{{ old('email', $marketing_member->email) }}" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" required />
                        @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">New Password (Optional)</span>
                        <input name="password" type="password" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" />
                        <span class="text-xs text-gray-500">Leave blank to keep current password</span>
                        @error('password') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700">Confirm New Password</span>
                        <input name="password_confirmation" type="password" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" />
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-hoot-dark border border-transparent rounded-lg active:bg-hoot-green hover:bg-hoot-green focus:outline-none focus:shadow-outline-green">
                    Update Member
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
