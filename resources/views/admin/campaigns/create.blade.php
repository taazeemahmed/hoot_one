<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.campaigns.index') }}" class="inline-flex items-center text-sm text-corp-500 hover:text-corp-700 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Campaigns
            </a>
        </div>

        <div class="bg-white rounded-xl border border-corp-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-corp-100">
                <h3 class="text-lg font-bold text-corp-900">Create Campaign</h3>
                <p class="text-sm text-corp-400 mt-1">The campaign name will be used as the Aisensy WhatsApp campaign name</p>
            </div>

            <form method="POST" action="{{ route('admin.campaigns.store') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-corp-700 mb-2">Campaign Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full border border-corp-200 rounded-xl px-4 py-3 text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50"
                           placeholder="e.g. renewal_reminder_march">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-corp-400">This name must match your Aisensy template campaign name exactly</p>
                </div>

                <!-- Media Attachment -->
                <div class="mb-6" x-data="mediaSelector()">
                    <label class="block text-sm font-semibold text-corp-700 mb-2">Campaign Image (optional)</label>
                    <p class="text-xs text-corp-400 mb-3">Required if your Aisensy template includes a header image</p>

                    <!-- Tabs -->
                    <div class="flex border-b border-corp-200 mb-4">
                        <button type="button" @click="tab = 'library'" :class="tab === 'library' ? 'border-corp-600 text-corp-600' : 'border-transparent text-corp-400 hover:text-corp-600'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                            From Library
                        </button>
                        <button type="button" @click="tab = 'upload'" :class="tab === 'upload' ? 'border-corp-600 text-corp-600' : 'border-transparent text-corp-400 hover:text-corp-600'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                            Upload New
                        </button>
                        <button type="button" @click="tab = 'url'" :class="tab === 'url' ? 'border-corp-600 text-corp-600' : 'border-transparent text-corp-400 hover:text-corp-600'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                            Paste URL
                        </button>
                    </div>

                    <!-- Library picker -->
                    <div x-show="tab === 'library'" x-cloak>
                        @if($mediaFiles->count() > 0)
                        <div class="grid grid-cols-4 gap-3 max-h-60 overflow-y-auto p-1">
                            @foreach($mediaFiles as $media)
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="media_url" value="{{ $media->url }}" class="sr-only peer" @change="selectedPreview = '{{ $media->url }}'; clearFileInput()">
                                <div class="aspect-square rounded-lg overflow-hidden border-2 border-transparent peer-checked:border-corp-600 peer-checked:ring-2 peer-checked:ring-corp-200 transition-all">
                                    <img src="{{ $media->url }}" alt="{{ $media->name }}" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-1 text-[10px] text-gray-500 truncate text-center">{{ $media->name }}</p>
                            </label>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-400 py-4 text-center">No images in library yet. Upload one below or go to <a href="{{ route('admin.media.index') }}" class="text-corp-600 underline">Media Library</a>.</p>
                        @endif
                    </div>

                    <!-- Upload new -->
                    <div x-show="tab === 'upload'" x-cloak>
                        <input type="file" name="media_file" accept="image/*" x-ref="fileInput"
                               @change="previewFile($event); clearUrlInput()"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-corp-50 file:text-corp-700 hover:file:bg-corp-100">
                        <p class="mt-1.5 text-xs text-gray-400">JPG, PNG, GIF, WebP — max 10 MB. Will also be saved to Media Library.</p>
                    </div>

                    <!-- URL input -->
                    <div x-show="tab === 'url'" x-cloak>
                        <input type="url" name="media_url" x-ref="urlInput" placeholder="https://example.com/image.jpg"
                               @input="selectedPreview = $event.target.value; clearFileInput()"
                               class="w-full border border-corp-200 rounded-xl px-4 py-2.5 text-sm focus:border-corp-500 focus:ring focus:ring-corp-200 focus:ring-opacity-50">
                        <p class="mt-1.5 text-xs text-gray-400">Publicly accessible direct image URL</p>
                    </div>

                    @error('media_url')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('media_file')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Preview -->
                    <template x-if="selectedPreview">
                        <div class="mt-3 flex items-center gap-3">
                            <img :src="selectedPreview" class="w-16 h-16 rounded-lg object-cover border border-corp-200">
                            <div>
                                <p class="text-xs font-medium text-corp-700">Image selected</p>
                                <button type="button" @click="clearAll()" class="text-xs text-red-500 hover:text-red-700 underline">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-corp-600 hover:bg-corp-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        Create Campaign
                    </button>
                    <a href="{{ route('admin.campaigns.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function mediaSelector() {
            return {
                tab: 'library',
                selectedPreview: null,
                previewFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedPreview = URL.createObjectURL(file);
                    }
                },
                clearFileInput() {
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                },
                clearUrlInput() {
                    if (this.$refs.urlInput) this.$refs.urlInput.value = '';
                    document.querySelectorAll('input[name="media_url"][type="radio"]').forEach(r => r.checked = false);
                },
                clearAll() {
                    this.selectedPreview = null;
                    this.clearFileInput();
                    this.clearUrlInput();
                    document.querySelectorAll('input[name="media_url"][type="radio"]').forEach(r => r.checked = false);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
