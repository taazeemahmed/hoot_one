<x-app-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-medium text-gray-700">Settings</h3>
        <p class="mt-1 text-sm text-gray-500">Configure application settings and integrations</p>
    </div>

    <!-- AISENSY Configuration -->
    <div class="p-6 bg-white rounded-lg shadow-md mb-8">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">AISENSY WhatsApp Integration</h4>
        
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label class="block text-sm text-gray-700">API Key</label>
                    <input type="password" name="aisensy_api_key" value="{{ $settings['aisensy_api_key'] ?? '' }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Campaign Name (30-Day Reminder)</label>
                    <input type="text" name="aisensy_campaign_name" value="{{ $settings['aisensy_campaign_name'] ?? 'test_richmond' }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Default: test_richmond</p>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Health Check Campaign (25-Day)</label>
                    <input type="text" name="aisensy_health_check_campaign" value="{{ $settings['aisensy_health_check_campaign'] ?? 'health_check' }}" class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">For patients after Jan 8, 2026</p>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Base URL</label>
                    <input type="text" name="aisensy_base_url" value="{{ $settings['aisensy_base_url'] ?? 'https://backend.aisensy.com' }}" disabled class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-hoot-dark hover:bg-hoot-green">Save Configuration</button>
            </div>
        </form>
    </div>

    <!-- Test Connection -->
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Test Connection</h4>
        <form action="{{ route('admin.settings.test') }}" method="POST">
            @csrf
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm text-gray-700">Test Phone Number (with Country Code)</label>
                    <input type="text" name="test_phone" placeholder="e.g. 15551234567" required class="block w-full mt-1 border-gray-300 rounded-md focus:border-hoot-green focus:ring focus:ring-hoot-green focus:ring-opacity-50">
                </div>
                <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg bg-gray-600 hover:bg-gray-700 h-10">Send Start Message</button>
            </div>
        </form>
    </div>
</x-app-layout>
