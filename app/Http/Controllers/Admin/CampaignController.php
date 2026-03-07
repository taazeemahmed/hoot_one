<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Patient;
use App\Models\Order;
use App\Models\Representative;
use App\Models\Medicine;
use App\Models\MediaFile;
use App\Services\AisensyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('creator')
            ->latest()
            ->paginate(15);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $mediaFiles = MediaFile::where('mime_type', 'like', 'image/%')->latest()->get();
        return view('admin.campaigns.create', compact('mediaFiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'media_url' => 'nullable|url|max:2048',
            'media_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $mediaUrl = null;
        $mediaFilename = null;

        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $path = $file->store('media', 'public');
            $url = \Storage::disk('public')->url($path);

            MediaFile::create([
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'filename' => $file->getClientOriginalName(),
                'disk' => 'public',
                'path' => $path,
                'url' => $url,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);

            $mediaUrl = $url;
            $mediaFilename = $file->getClientOriginalName();
        } elseif (!empty($validated['media_url'])) {
            $mediaUrl = $validated['media_url'];
            $mediaFilename = basename(parse_url($mediaUrl, PHP_URL_PATH));
        }

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'media_url' => $mediaUrl,
            'media_filename' => $mediaFilename,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campaign created. Now select recipients.');
    }

    public function show(Request $request, Campaign $campaign)
    {
        // Build patient query with filters
        $query = Patient::query()
            ->with(['representative.user', 'latestOrder.medicine']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Representative filter
        if ($request->filled('representative_id')) {
            $query->where('representative_id', $request->representative_id);
        }

        // Medicine filter (via orders)
        if ($request->filled('medicine_id')) {
            $medicineId = $request->medicine_id;
            $query->whereHas('orders', function ($q) use ($medicineId) {
                $q->where('medicine_id', $medicineId);
            });
        }

        // Order status filter
        if ($request->filled('order_status')) {
            $status = $request->order_status;
            if ($status === 'overdue') {
                $query->whereHas('orders', function ($q) {
                    $q->where('status', 'active')
                      ->where('expected_renewal_date', '<', Carbon::today());
                });
            } elseif ($status === 'active') {
                $query->whereHas('orders', function ($q) {
                    $q->where('status', 'active');
                });
            }
        }

        // Custom date range (renewal date)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = $request->date_from;
            $to = $request->date_to;
            $query->whereHas('orders', function ($q) use ($from, $to) {
                $q->where('status', 'active')
                  ->whereBetween('expected_renewal_date', [$from, $to]);
            });
        }

        $patients = $query->orderBy('name')->paginate(50)->withQueryString();

        // Already added phones for this campaign (for duplicate check display)
        $existingPhones = $campaign->recipients()->pluck('phone')->toArray();

        $representatives = Representative::with('user')->where('status', 'active')->get();
        $medicines = Medicine::active()->orderBy('name')->get();

        $campaign->load('recipients');

        $pendingCount = $campaign->recipients()->where('status', 'pending')->count();

        return view('admin.campaigns.show', compact(
            'campaign', 'patients', 'representatives', 'medicines', 'existingPhones', 'pendingCount'
        ));
    }

    /**
     * Add selected patients as campaign recipients.
     */
    public function addRecipients(Request $request, Campaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return back()->with('error', 'Cannot modify a campaign while it is being sent.');
        }

        $validated = $request->validate([
            'patient_ids' => 'required|array|min:1',
            'patient_ids.*' => 'exists:patients,id',
        ]);

        $added = 0;
        $skipped = 0;

        foreach ($validated['patient_ids'] as $patientId) {
            $patient = Patient::find($patientId);
            if (!$patient || !$patient->phone) continue;

            $phone = preg_replace('/[^0-9]/', '', $patient->phone);

            // Check duplicate within this campaign
            $exists = CampaignRecipient::where('campaign_id', $campaign->id)
                ->where('phone', $phone)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            CampaignRecipient::create([
                'campaign_id' => $campaign->id,
                'patient_id' => $patient->id,
                'phone' => $phone,
                'status' => 'pending',
            ]);
            $added++;
        }

        $campaign->update([
            'total_recipients' => $campaign->recipients()->count(),
        ]);

        $msg = "{$added} recipient(s) added.";
        if ($skipped > 0) {
            $msg .= " {$skipped} duplicate(s) skipped.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove a recipient from the campaign.
     */
    public function removeRecipient(Campaign $campaign, CampaignRecipient $recipient)
    {
        if ($campaign->status === 'sending') {
            return back()->with('error', 'Cannot modify a campaign while it is being sent.');
        }

        if ($recipient->status !== 'pending') {
            return back()->with('error', 'Only pending recipients can be removed.');
        }

        $recipient->delete();

        $campaign->update([
            'total_recipients' => $campaign->recipients()->count(),
        ]);

        return back()->with('success', 'Recipient removed.');
    }

    /**
     * Send the campaign via Aisensy WhatsApp API.
     * Returns JSON for AJAX progress tracking.
     */
    public function send(Campaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return response()->json(['error' => 'Campaign is already being sent.'], 422);
        }

        $pendingRecipients = $campaign->recipients()->where('status', 'pending')->get();

        if ($pendingRecipients->isEmpty()) {
            return response()->json(['error' => 'No pending recipients to send to.'], 422);
        }

        // Also check cross-campaign duplicates: skip if same campaign name + same phone was sent before
        $previouslySent = CampaignRecipient::whereHas('campaign', function ($q) use ($campaign) {
                $q->where('name', $campaign->name)
                  ->where('id', '!=', $campaign->id);
            })
            ->where('status', 'success')
            ->pluck('phone')
            ->toArray();

        $campaign->update([
            'status' => 'sending',
            'sent_at' => now(),
            'sent_count' => 0,
            'total_recipients' => $pendingRecipients->count(),
        ]);

        $aisensy = new AisensyService();
        $successCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        foreach ($pendingRecipients as $recipient) {
            // Cross-campaign duplicate prevention
            if (in_array($recipient->phone, $previouslySent)) {
                $recipient->update([
                    'status' => 'skipped',
                    'error_message' => 'Already sent in a previous campaign with same name',
                    'api_response' => [
                        'type' => 'duplicate_prevention',
                        'reason' => 'Already sent in a previous campaign with same name',
                        'campaign_name' => $campaign->name,
                        'phone' => $recipient->phone,
                    ],
                    'sent_at' => now(),
                ]);
                $skippedCount++;
                $campaign->increment('sent_count');
                $campaign->increment('skipped_count');
                continue;
            }

            try {
                $result = $aisensy->sendMessage(
                    $recipient->phone,
                    [$recipient->patient->name ?? 'Customer'],
                    $campaign->name,
                    $campaign->media_url
                );

                if ($result['success']) {
                    $recipient->update([
                        'status' => 'success',
                        'api_response' => $result['data'] ?? null,
                        'sent_at' => now(),
                    ]);
                    $successCount++;
                    $campaign->increment('success_count');
                } else {
                    $recipient->update([
                        'status' => 'failed',
                        'error_message' => $result['error'] ?? 'Unknown error',
                        'api_response' => $result['details'] ?? $result,
                        'sent_at' => now(),
                    ]);
                    $failedCount++;
                    $campaign->increment('failed_count');
                }
            } catch (\Throwable $e) {
                Log::error("Campaign send error for phone {$recipient->phone}: " . $e->getMessage());
                $recipient->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'api_response' => [
                        'type' => 'exception',
                        'campaign_name' => $campaign->name,
                        'phone' => $recipient->phone,
                        'exception_class' => get_class($e),
                        'exception_message' => $e->getMessage(),
                        'exception_file' => $e->getFile(),
                        'exception_line' => $e->getLine(),
                    ],
                    'sent_at' => now(),
                ]);
                $failedCount++;
                $campaign->increment('failed_count');
            }

            $campaign->increment('sent_count');
        }

        $campaign->update(['status' => 'draft']);

        return response()->json([
            'success' => true,
            'total' => $pendingRecipients->count(),
            'sent' => $successCount,
            'failed' => $failedCount,
            'skipped' => $skippedCount,
        ]);
    }

    /**
     * Get campaign progress (for AJAX polling).
     */
    public function progress(Campaign $campaign)
    {
        $campaign->refresh();

        return response()->json([
            'status' => $campaign->status,
            'total' => $campaign->total_recipients,
            'sent_count' => $campaign->sent_count,
            'success_count' => $campaign->success_count,
            'failed_count' => $campaign->failed_count,
            'skipped_count' => $campaign->skipped_count,
            'progress_percent' => $campaign->progress_percent,
        ]);
    }

    /**
     * View campaign logs with full error details.
     */
    public function logs(Campaign $campaign)
    {
        $recipients = $campaign->recipients()
            ->with('patient')
            ->orderByRaw("FIELD(status, 'failed', 'skipped', 'success', 'pending')")
            ->paginate(50);

        return view('admin.campaigns.logs', compact('campaign', 'recipients'));
    }

    /**
     * Reset all failed/skipped recipients to pending so they can be retried.
     */
    public function retryFailed(Campaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return back()->with('error', 'Cannot modify a campaign while it is being sent.');
        }

        $count = $campaign->recipients()
            ->whereIn('status', ['failed', 'skipped'])
            ->update([
                'status' => 'pending',
                'error_message' => null,
                'api_response' => null,
                'sent_at' => null,
            ]);

        return back()->with('success', "{$count} recipient(s) reset to pending and ready for next send.");
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return back()->with('error', 'Cannot delete a campaign while it is sending.');
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted.');
    }
}
