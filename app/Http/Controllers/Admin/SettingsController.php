<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\AisensyService;

class SettingsController extends Controller
{
    public function index()
    {
        $settingsKeys = ['aisensy_api_key', 'aisensy_campaign_name', 'aisensy_health_check_campaign'];
        $settings = Setting::whereIn('key', $settingsKeys)->pluck('value', 'key')->toArray();

        // Ensure defaults are present in the array if not in DB
        $settings['aisensy_campaign_name'] = $settings['aisensy_campaign_name'] ?? 'test_richmond';
        $settings['aisensy_health_check_campaign'] = $settings['aisensy_health_check_campaign'] ?? 'health_check';
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'aisensy_api_key' => 'nullable|string',
            'aisensy_campaign_name' => 'required|string',
            'aisensy_health_check_campaign' => 'required|string',
        ]);

        if ($request->filled('aisensy_api_key')) {
            Setting::set('aisensy_api_key', $request->aisensy_api_key);
        }

        Setting::set('aisensy_campaign_name', $request->aisensy_campaign_name);
        Setting::set('aisensy_health_check_campaign', $request->aisensy_health_check_campaign);

        return redirect()->back()->with('success', 'Configuration saved successfully.');
    }

    public function test(Request $request, AisensyService $aisensy)
    {
        $request->validate([
            'test_phone' => 'required|string',
        ]);

        // Try sending with NO parameters first, as many test campaigns are static (0 params).
        // If your campaign has variables like {{1}}, you need to match the count.
        $response = $aisensy->sendMessage($request->test_phone, []);

        if ($response['success']) {
            return redirect()->back()->with('success', 'Test message sent successfully! Check Logs for details.');
        } else {
            return redirect()->back()->with('error', 'Failed to send message: ' . ($response['error'] ?? 'Unknown error'));
        }
    }
}
