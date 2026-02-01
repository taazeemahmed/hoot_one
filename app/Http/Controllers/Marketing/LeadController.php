<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\LeadActivity;
use App\Models\Representative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::query()->with(['representative.user', 'latestOrder']);

        if ($request->has('status')) {
            $query->where('lead_status', $request->status);
        }
        if ($request->has('quality')) {
            $query->where('lead_quality', $request->quality);
        }

        $leads = $query->latest()->paginate(15);
        
        // Metrics
        $totalCount = Patient::count();
        $hotCount = Patient::where('lead_quality', 'hot')->count();
        $newCount = Patient::where('lead_status', 'new')->count();
        $assignedCount = Patient::where('lead_status', 'assigned')->count();

        // For assignment modal
        $representatives = Representative::with('user')->where('status', 'active')->get();

        return view('marketing.leads.index', compact('leads', 'representatives', 'totalCount', 'hotCount', 'newCount', 'assignedCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $representatives = Representative::with('user')->where('status', 'active')->get();
        return view('marketing.leads.create', compact('representatives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'country' => 'required|string', // Still needed for patient record
            'representative_id' => 'required|exists:representatives,id',
            'lead_quality' => 'required|in:hot,warm,cold,invalid',
            'source' => 'required|in:whatsapp,call,website,referral',
            'medical_concern' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $representative = Representative::find($validated['representative_id']);

        // Append medical concern to notes
        $finalNotes = "Medical Concern: " . $validated['medical_concern'];
        if (!empty($validated['notes'])) {
            $finalNotes .= "\n---\n" . $validated['notes'];
        }

        // Status is always assigned because we force picking a rep
        $leadStatus = 'assigned';
        
        $patient = Patient::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'representative_id' => $representative->id,
            'lead_quality' => $validated['lead_quality'],
            'source' => $validated['source'],
            'notes' => $finalNotes,
            'lead_status' => $leadStatus,
            'assigned_at' => now(),
            'assigned_by' => Auth::id(),
        ]);

        LeadActivity::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'type' => 'assignment',
            'result' => 'assigned',
            'notes' => 'Directly assigned to Representative: ' . $representative->user->name,
        ]);

        return redirect()->route('marketing.leads.index')->with('success', 'Lead created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $lead)
    {
        return view('marketing.leads.edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'country' => 'required|string',
            'lead_quality' => 'required|in:hot,warm,cold,invalid',
            'source' => 'required|in:whatsapp,call,website,referral',
            'notes' => 'nullable|string'
        ]);

        $lead->update($validated);

        return redirect()->route('marketing.leads.index')->with('success', 'Lead updated successfully.');
    }

    /**
     * Assign lead to representative
     */
    public function assign(Request $request, Patient $lead)
    {
        $request->validate([
            'representative_id' => 'required|exists:representatives,id'
        ]);

        $lead->update([
            'representative_id' => $request->representative_id,
            'lead_status' => 'assigned',
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
        ]);

        LeadActivity::create([
            'patient_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'assignment',
            'result' => 'assigned',
            'notes' => 'Assigned to Representative ID: ' . $request->representative_id
        ]);

        return back()->with('success', 'Lead assigned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $lead)
    {
        // Delete related activities first
        LeadActivity::where('patient_id', $lead->id)->delete();
        
        $leadName = $lead->name;
        $lead->delete();

        return back()->with('success', "Lead '{$leadName}' deleted successfully.");
    }
}
