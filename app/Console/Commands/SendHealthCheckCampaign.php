<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\WhatsappLog;
use App\Services\AisensyService;
use App\Models\Setting;
use Carbon\Carbon;

class SendHealthCheckCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-health-check-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send health check WhatsApp campaign to patients from Jan 8, 2026 every 25 days';

    /**
     * Execute the console command.
     */
    public function handle(AisensyService $aisensy)
    {
        $this->info('Starting Health Check Campaign...');

        $cutoffDate = Carbon::create(2026, 1, 8, 0, 0, 0);
        $campaignName = Setting::get('aisensy_health_check_campaign', 'health_check');

        // Find active orders created/started on or after the cutoff date
        $orders = Order::where('status', 'active')
            ->whereDate('treatment_start_date', '>=', $cutoffDate)
            ->with('patient')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            $startDate = $order->treatment_start_date;
            $diffInDays = (int) $startDate->diffInDays(now());

            // Check if today is a 25-day multiple (25, 50, 75...)
            // And ensure we are at least at day 25
            if ($diffInDays >= 25 && $diffInDays % 25 === 0) {
                
                // Double check we haven't sent a reminder related to this order TODAY
                // We use a specific message body tag to differentiate in logs
                $messageTag = "Health Check Day {$diffInDays}";
                
                $alreadySent = WhatsappLog::where('order_id', $order->id)
                    ->where('patient_id', $order->patient_id)
                    ->whereDate('sent_at', now())
                    ->where('message_body', $messageTag) 
                    ->exists();

                if ($alreadySent) {
                    $this->info("Skipping order {$order->id}: already sent today.");
                    continue;
                }

                $this->info("Sending Health Check for Order #{$order->id} (Day {$diffInDays})");

                $patient = $order->patient;
                $phone = $patient->phone;
                $name = $patient->name;

                // Send Message
                // We use the specific campaign name for this flow
                $response = $aisensy->sendMessage($phone, [$name], $campaignName);
                $status = $response['success'] ? 'sent' : 'failed';

                // Log it
                WhatsappLog::create([
                    'patient_id' => $patient->id,
                    'order_id' => $order->id,
                    'phone_number' => $phone,
                    'message_body' => $messageTag,
                    'status' => $status,
                    'response' => $response['data'] ?? ['error' => $response['error'] ?? 'Unknown'],
                    'sent_at' => now(),
                ]);

                $count++;
            }
        }

        $this->info("Completed. Sent {$count} health check messages.");
    }
}
