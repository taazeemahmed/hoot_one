<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\WhatsappLog;
use App\Services\AisensyService;
use Carbon\Carbon;

class SendWhatsappReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-whatsapp-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders to patients on 30-day intervals from treatment start';

    /**
     * Execute the console command.
     */
    public function handle(AisensyService $aisensy)
    {
        $this->info('Starting WhatsApp Reminders check...');

        // Find active orders
        $orders = Order::where('status', 'active')
            ->with('patient')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            $startDate = $order->treatment_start_date;
            $diffInDays = (int) $startDate->diffInDays(now());

            // Check if today is a 30-day multiple (30, 60, 90...)
            // And ensure we are at least at day 30
            if ($diffInDays >= 30 && $diffInDays % 30 === 0) {
                // Determine if we should send
                // (Optional: Check if we already sent for this cycle? 
                // Since this runs daily, we can check if a log exists for today/order combination 
                // to avoid double sending if run twice, but typically cron runs once.)
                
                // Double check we haven't sent a reminder related to this order TODAY
                $alreadySent = WhatsappLog::where('order_id', $order->order_id) // using relation if possible, or just patient_id/date
                    ->where('patient_id', $order->patient_id)
                    ->whereDate('sent_at', now())
                    ->exists();

                if ($alreadySent) {
                    $this->info("Skipping order {$order->id}: already sent today.");
                    continue;
                }

                $this->info("Sending reminder for Order #{$order->id} (Day {$diffInDays})");

                $patient = $order->patient;
                $phone = $patient->phone; // Validation logic will improve this later
                $name = $patient->name;

                // Send Message
                // Variables: assuming static template based on recent error
                $response = $aisensy->sendMessage($phone, []);
                $status = $response['success'] ? 'sent' : 'failed';

                // Log it
                WhatsappLog::create([
                    'patient_id' => $patient->id,
                    'order_id' => $order->id,
                    'phone_number' => $phone,
                    'message_body' => "Reminder Day {$diffInDays}",
                    'status' => $status,
                    'response' => $response['data'] ?? ['error' => $response['error'] ?? 'Unknown'],
                    'sent_at' => now(),
                ]);

                $count++;
            }
        }

        $this->info("Completed. Sent {$count} reminders.");
    }
}
