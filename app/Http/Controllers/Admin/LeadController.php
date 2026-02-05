<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\LeadActivity;
use App\Models\Representative;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeadController extends Controller
{
    /**
     * Display a listing of all leads.
     * Admin can see ALL leads across the system.
     */
    public function index(Request $request)
    {
        $query = Patient::query()
            ->with(['representative.user', 'assignedBy', 'latestActivity', 'latestOrder']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('lead_status', $request->status);
        }
        
        // Filter by quality
        if ($request->has('quality') && $request->quality) {
            $query->where('lead_quality', $request->quality);
        }
        
        // Filter by source (marketing member)
        if ($request->has('source_user') && $request->source_user) {
            if ($request->source_user === 'company_direct') {
                // Company Direct = assigned by super_admin
                $adminIds = User::where('role', 'super_admin')->pluck('id');
                $query->whereIn('assigned_by', $adminIds);
            } else {
                $query->where('assigned_by', $request->source_user);
            }
        }
        
        // Filter by representative
        if ($request->has('representative_id') && $request->representative_id) {
            $query->where('representative_id', $request->representative_id);
        }
        
        // Filter by country
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        $leads = $query->latest()->paginate(20);
        
        // Metrics
        $metrics = [
            'total' => Patient::count(),
            'new' => Patient::where('lead_status', 'new')->count(),
            'assigned' => Patient::where('lead_status', 'assigned')->count(),
            'contacted' => Patient::where('lead_status', 'contacted')->count(),
            'negotiating' => Patient::where('lead_status', 'negotiating')->count(),
            'converted' => Patient::where('lead_status', 'converted')->count(),
            'lost' => Patient::where('lead_status', 'lost')->count(),
            'hot' => Patient::where('lead_quality', 'hot')->count(),
            'company_direct' => Patient::whereIn('assigned_by', User::where('role', 'super_admin')->pluck('id'))->count(),
        ];

        // For filters
        $representatives = Representative::with('user')->where('status', 'active')->get();
        $marketingMembers = User::where('role', 'marketing_member')->get();
        $countries = Patient::distinct()->pluck('country')->filter()->values();

        return view('admin.leads.index', compact('leads', 'metrics', 'representatives', 'marketingMembers', 'countries'));
    }

    /**
     * Show the form for creating a new lead (Company Direct).
     */
    public function create()
    {
        $representatives = Representative::with('user')->where('status', 'active')->get();
        return view('admin.leads.create', compact('representatives'));
    }

    /**
     * Store a newly created lead (Company Direct).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'country' => 'required|string',
            'representative_id' => 'nullable|exists:representatives,id',
            'lead_quality' => 'required|in:hot,warm,cold,invalid',
            'source' => 'required|in:whatsapp,call,website,referral',
            'medical_concern' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // Build notes
        $finalNotes = "Medical Concern: " . $validated['medical_concern'];
        if (!empty($validated['notes'])) {
            $finalNotes .= "\n---\n" . $validated['notes'];
        }

        // Determine status based on whether representative is assigned
        $leadStatus = $validated['representative_id'] ? 'assigned' : 'new';
        
        $patient = Patient::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'representative_id' => $validated['representative_id'],
            'lead_quality' => $validated['lead_quality'],
            'source' => $validated['source'],
            'notes' => $finalNotes,
            'lead_status' => $leadStatus,
            'assigned_at' => $validated['representative_id'] ? now() : null,
            'assigned_by' => Auth::id(), // Company Direct - assigned by admin
        ]);

        LeadActivity::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'type' => 'note',
            'result' => 'Lead created (Company Direct)',
            'notes' => 'Created by Admin as Company Direct lead',
        ]);

        if ($validated['representative_id']) {
            LeadActivity::create([
                'patient_id' => $patient->id,
                'user_id' => Auth::id(),
                'type' => 'assignment',
                'result' => 'assigned',
                'notes' => 'Assigned to Representative on creation',
            ]);
        }

        return redirect()->route('admin.leads.index')->with('success', 'Company Direct lead created successfully.');
    }

    /**
     * Display the specified lead with full activity history.
     */
    public function show(Patient $lead)
    {
        $lead->load(['representative.user', 'assignedBy', 'activities.user', 'orders.medicine']);
        $medicines = Medicine::active()->get();
        $representatives = Representative::with('user')->where('status', 'active')->get();
        
        return view('admin.leads.show', compact('lead', 'medicines', 'representatives'));
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Patient $lead)
    {
        $representatives = Representative::with('user')->where('status', 'active')->get();
        $medicines = Medicine::active()->get();
        return view('admin.leads.edit', compact('lead', 'representatives', 'medicines'));
    }

    /**
     * Update the specified lead.
     */
    public function update(Request $request, Patient $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'country' => 'required|string',
            'representative_id' => 'nullable|exists:representatives,id',
            'lead_quality' => 'required|in:hot,warm,cold,invalid',
            'lead_status' => 'required|in:new,assigned,contacted,negotiating,converted,lost,not_interested',
            'source' => 'required|in:whatsapp,call,website,referral',
            'notes' => 'nullable|string',
            // Conversion fields
            'medicine_id' => 'required_if:lead_status,converted|nullable|exists:medicines,id',
            'packs_ordered' => 'required_if:lead_status,converted|nullable|integer|min:1',
        ]);

        $oldStatus = $lead->lead_status;
        $oldRepId = $lead->representative_id;

        $lead->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'representative_id' => $validated['representative_id'],
            'lead_quality' => $validated['lead_quality'],
            'lead_status' => $validated['lead_status'],
            'source' => $validated['source'],
            'notes' => $validated['notes'],
        ]);

        // Log assignment if representative changed
        if ($validated['representative_id'] && $oldRepId !== $validated['representative_id']) {
            $lead->update(['assigned_at' => now()]);
            
            LeadActivity::create([
                'patient_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'assignment',
                'result' => 'reassigned',
                'notes' => 'Reassigned by Admin to Representative ID: ' . $validated['representative_id'],
            ]);
        }

        // Handle conversion
        if ($validated['lead_status'] === 'converted' && $oldStatus !== 'converted') {
            $medicine = Medicine::find($validated['medicine_id']);
            if ($medicine) {
                $startDate = Carbon::today();
                $renewalDate = Order::calculateRenewalDate(
                    $startDate,
                    $validated['packs_ordered'],
                    $medicine->pack_duration_days
                );

                Order::create([
                    'patient_id' => $lead->id,
                    'medicine_id' => $medicine->id,
                    'representative_id' => $lead->representative_id,
                    'packs_ordered' => $validated['packs_ordered'],
                    'treatment_start_date' => $startDate,
                    'expected_renewal_date' => $renewalDate,
                    'notes' => 'Initial order on conversion by Admin',
                    'status' => 'active',
                ]);

                LeadActivity::create([
                    'patient_id' => $lead->id,
                    'user_id' => Auth::id(),
                    'type' => 'conversion',
                    'result' => 'converted',
                    'notes' => 'Converted to patient by Admin',
                ]);
            }
        }

        return redirect()->route('admin.leads.index')->with('success', 'Lead updated successfully.');
    }

    /**
     * Log an activity for a lead.
     */
    public function storeActivity(Request $request, Patient $lead)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,whatsapp,note,assignment',
            'result' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'follow_up_at' => 'nullable|date',
        ]);

        LeadActivity::create([
            'patient_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'result' => $validated['result'],
            'notes' => $validated['notes'],
            'follow_up_at' => $validated['follow_up_at'] ?? null,
        ]);

        return back()->with('success', 'Activity logged successfully.');
    }

    /**
     * Assign a lead to a representative.
     */
    public function assign(Request $request, Patient $lead)
    {
        $validated = $request->validate([
            'representative_id' => 'required|exists:representatives,id'
        ]);

        $lead->update([
            'representative_id' => $validated['representative_id'],
            'lead_status' => 'assigned',
            'assigned_at' => now(),
        ]);

        LeadActivity::create([
            'patient_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'assignment',
            'result' => 'assigned',
            'notes' => 'Assigned by Admin to Representative ID: ' . $validated['representative_id'],
        ]);

        return back()->with('success', 'Lead assigned successfully.');
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Patient $lead)
    {
        // Delete related data
        LeadActivity::where('patient_id', $lead->id)->delete();
        Order::where('patient_id', $lead->id)->delete();
        
        $leadName = $lead->name;
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', "Lead '{$leadName}' deleted successfully.");
    }
}
