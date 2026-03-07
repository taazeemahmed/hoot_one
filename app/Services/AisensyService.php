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
    public function sendMessage($to, $params = [], $campaignOverride = null, $mediaUrl = null)
    {
        $campaign = $campaignOverride ?? $this->campaignName;
        $apiKey = $this->apiKey;

        if (!$apiKey) {
            return [
                'success' => false,
                'error' => 'API Key not configured',
                'details' => [
                    'type' => 'configuration_error',
                    'endpoint' => null,
                    'campaign_name' => $campaign,
                    'destination' => $to,
                ],
            ];
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

        // Try payload variants to handle campaign templates with different expected param counts.
        $primaryParams = array_values($params);
        $strategies = [$primaryParams];

        if (!empty($primaryParams)) {
            $strategies[] = [];
        } else {
            $strategies[] = ['Customer'];
        }

        // Remove duplicate strategies while preserving order.
        $normalized = [];
        foreach ($strategies as $strategy) {
            $key = json_encode($strategy);
            if (!isset($normalized[$key])) {
                $normalized[$key] = $strategy;
            }
        }

        $attempt = 0;
        $lastFailure = null;
        foreach (array_values($normalized) as $templateParams) {
            $attempt++;
            $result = $this->sendAttempt($endpoint, $apiKey, $campaign, $to, $templateParams, $attempt, $mediaUrl);

            if ($result['success']) {
                return $result;
            }

            $lastFailure = $result;
            $isTemplateMismatch = $this->isTemplateParamMismatch($result);
            if (!$isTemplateMismatch) {
                return $result;
            }
        }

        return $lastFailure ?? [
            'success' => false,
            'error' => 'Unknown Aisensy send failure',
            'details' => [
                'type' => 'unknown_error',
                'endpoint' => $endpoint,
                'campaign_name' => $campaign,
                'destination' => $to,
            ],
        ];
    }

    private function sendAttempt($endpoint, $apiKey, $campaign, $to, array $templateParams, $attempt, $mediaUrl = null)
    {
        $media = [];
        if ($mediaUrl) {
            $media = [
                'url' => $mediaUrl,
                'filename' => basename(parse_url($mediaUrl, PHP_URL_PATH)),
            ];
        }

        $payload = [
            'apiKey' => $apiKey,
            'campaignName' => $campaign,
            'destination' => $to,
            'userName' => $templateParams[0] ?? 'Customer',
            'templateParams' => $templateParams,
            'source' => $this->source,
            'media' => $media,
        ];

        try {
            $response = Http::timeout(30)->post($endpoint, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'details' => [
                        'endpoint' => $endpoint,
                        'campaign_name' => $campaign,
                        'destination' => $to,
                        'http_status' => $response->status(),
                        'attempt' => $attempt,
                        'template_param_count' => count($templateParams),
                        'template_params' => $templateParams,
                    ],
                ];
            }

            Log::error('AISENSY API Error: ' . $response->body());

            $responseJson = null;
            try {
                $responseJson = $response->json();
            } catch (\Throwable $e) {
                $responseJson = null;
            }

            return [
                'success' => false,
                'error' => $response->body(),
                'details' => [
                    'type' => 'http_error',
                    'endpoint' => $endpoint,
                    'campaign_name' => $campaign,
                    'destination' => $to,
                    'http_status' => $response->status(),
                    'reason_phrase' => $response->reason(),
                    'response_body' => $response->body(),
                    'response_json' => $responseJson,
                    'attempt' => $attempt,
                    'template_param_count' => count($templateParams),
                    'template_params' => $templateParams,
                ],
            ];
        } catch (\Throwable $e) {
            Log::error('AISENSY Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'details' => [
                    'type' => 'exception',
                    'endpoint' => $endpoint,
                    'campaign_name' => $campaign,
                    'destination' => $to,
                    'exception_class' => get_class($e),
                    'exception_message' => $e->getMessage(),
                    'exception_file' => $e->getFile(),
                    'exception_line' => $e->getLine(),
                    'attempt' => $attempt,
                    'template_param_count' => count($templateParams),
                    'template_params' => $templateParams,
                ],
            ];
        }
    }

    private function isTemplateParamMismatch(array $result): bool
    {
        $error = strtolower((string) ($result['error'] ?? ''));
        $details = $result['details'] ?? [];
        $responseMessage = strtolower((string) (($details['response_json']['message'] ?? '') ?: ''));

        return str_contains($error, 'template params does not match the campaign')
            || str_contains($responseMessage, 'template params does not match the campaign');
    }
}
