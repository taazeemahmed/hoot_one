<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class AisensyService
{
    protected $apiKey;
    protected $baseUrl;
    protected $campaignName;
    protected $source;

    public function __construct()
    {
        $this->apiKey = Setting::get('aisensy_api_key');
        // Default to the user provided URL if not set
        $this->baseUrl = 'https://backend.aisensy.com'; 
        $this->campaignName = Setting::get('aisensy_campaign_name', 'test_richmond');
        $this->source = 'new_software'; // tagging source
    }

    /**
     * Send a WhatsApp message/campaign.
     *
     * @param string $to Phone number (with country code preferred)
     * @param array $params Template parameters (e.g. name)
     * @param string|null $campaignOverride Optional campaign name to override default
     * @return array Response data including success status
     */
    public function sendMessage($to, $params = [], $campaignOverride = null)
    {
        $campaign = $campaignOverride ?? $this->campaignName;
        $apiKey = $this->apiKey;

        if (!$apiKey) {
            return ['success' => false, 'error' => 'API Key not configured'];
        }

        // Normalize Phone Number
        // Remove spaces, dashes, parentheses
        $to = preg_replace('/[^0-9]/', '', $to);
        
        // If number does not start with a simplified country code (like 91 for India, 1 for US)
        // This is tricky without knowing the country. 
        // The user said: "by default assign country code as per country". 
        // We will implement better logic in the Controller to save full number.
        // Here we assume $to is valid E.164 without the +. 
        // AISENSY usually expects numbers like "15551234567" or "919876543210".
        // If it starts with + remove it (done by regex above).

        $endpoint = "{$this->baseUrl}/campaign/t1/api/v2";

        // Construct Payload based on AISENSY documentation practices for v2 API
        // Typically: apiKey, campaignName, destination, userName, templateParams
        // Note: The structure depends on the specific API version. 
        // Based on user request: "API Endpoint /campaign/t1/api"
        
        $payload = [
            'apiKey' => $apiKey,
            'campaignName' => $campaign,
            'destination' => $to,
            'userName' => $params[0] ?? 'Customer', // Fallback name
            'templateParams' => $params,
            'source' => $this->source,
            'media' => [] // If media is needed later
        ];

        try {
            $response = Http::timeout(30)->post($endpoint, $payload);
            
            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            } else {
                Log::error('AISENSY API Error: ' . $response->body());
                return ['success' => false, 'error' => $response->body()];
            }
        } catch (\Exception $e) {
            Log::error('AISENSY Exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
