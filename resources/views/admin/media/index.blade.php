<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h3 class="text-2xl font-bold text-corp-900">Media Library</h3>
                <p class="text-sm text-corp-400 mt-1">Upload and manage images for WhatsApp campaigns</p>
            </div>
            <button @click="$refs.uploadModal.showModal()" class="px-5 py-2.5 bg-corp-600 hover:bg-corp-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Upload File
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">{{ session('success') }}</div>
        @endif

        <!-- Search -->
        <div class="mb-6">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                       class="border-corp-200 rounded-xl text-sm px-4 py-2.5 w-full max-w-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50">
                <button type="submit" class="px-4 py-2.5 bg-corp-900 text-white text-sm font-semibold rounded-xl hover:bg-corp-800 transition-colors">Search</button>
                @if(request('search'))
                <a href="{{ route('admin.media.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200 transition-colors">Clear</a>
                @endif
            </form>
        </div>

        <!-- Media Grid -->
        @if($mediaFiles->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
            @foreach($mediaFiles as $media)
            <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden group" x-data="{ showActions: false }">
                <div class="relative aspect-square bg-gray-100" @mouseenter="showActions = true" @mouseleave="showActions = false">
                    @if($media->isImage())
                    <img src="{{ $media->url }}" alt="{{ $media->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    @endif

                    <!-- Hover actions overlay -->
                    <div x-show="showActions" x-cloak class="absolute inset-0 bg-black/50 flex items-center justify-center gap-2 transition-opacity">
                        <button @click="navigator.clipboard.writeText('{{ $media->url }}'); $dispatch('notify', {message: 'URL copied!'})"
                                class="p-2 bg-white rounded-lg text-corp-700 hover:bg-corp-50 transition-colors" title="Copy URL">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.media.destroy', $media) }}" onsubmit="return confirm('Delete this file?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-2.5">
                    <p class="text-xs font-medium text-gray-900 truncate" title="{{ $media->name }}">{{ $media->name }}</p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-[10px] text-gray-400 uppercase">{{ pathinfo($media->filename, PATHINFO_EXTENSION) }}</span>
                        <span class="text-[10px] text-gray-400">{{ $media->human_size }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{ $mediaFiles->links() }}
        @else
        <div class="bg-white rounded-xl border border-corp-100 shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-gray-500 font-medium">No media files yet</p>
            <p class="text-sm text-gray-400 mt-1">Upload your first image to get started</p>
        </div>
        @endif
    </div>

    <!-- Upload Modal -->
    <dialog x-ref="uploadModal" class="rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-md shadow-xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-5">
                <h4 class="text-lg font-bold text-corp-900">Upload File</h4>
                <button @click="$refs.uploadModal.close()" class="p-1 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-corp-700 mb-2">Name (optional)</label>
                    <input type="text" name="name" class="w-full border-corp-200 rounded-xl px-4 py-2.5 text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50" placeholder="e.g. March Promo Banner">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-corp-700 mb-2">File</label>
                    <input type="file" name="file" required accept="image/*,video/mp4,.pdf"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-corp-50 file:text-corp-700 hover:file:bg-corp-100">
                    <p class="mt-1.5 text-xs text-gray-400">JPG, PNG, GIF, WebP, MP4, PDF — max 10 MB</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-corp-600 hover:bg-corp-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">Upload</button>
                    <button type="button" @click="$refs.uploadModal.close()" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>
