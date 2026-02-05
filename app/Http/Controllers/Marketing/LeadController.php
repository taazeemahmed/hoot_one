<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\LeadActivity;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    private function getCompanyDirectRepresentative(): ?Representative
    {
        $superAdmin = User::query()
            ->where('role', 'super_admin')
            ->orderBy('id')
            ->first();

        if (!$superAdmin) {
            return null;
        }

        $rep = Representative::where('user_id', $superAdmin->id)->first();
        if ($rep) {
            // If this looks like our previously auto-created Company Direct rep, normalize its country.
            if ($rep->phone === 'N/A' && ($rep->country === 'Company Direct' || empty($rep->country))) {
                $rep->update(['country' => 'India']);
            }
            return $rep;
        }

        return Representative::create([
            'user_id' => $superAdmin->id,
            'country' => 'India',
            'country_code' => null,
            'region' => null,
            'phone' => 'N/A',
            'address' => null,
            'status' => 'active',
        ]);
    }

    /**
     * Display a listing of the resource.
     * CRITICAL: Marketing members can ONLY see leads they personally added (assigned_by = their user ID)
     * This isolation is mandatory to avoid internal conflicts.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Marketing members can ONLY see their own leads
        $query = Patient::query()
            ->where('assigned_by', $userId)
            ->with(['representative.user', 'latestOrder']);

        if ($request->has('status') && $request->status) {
            $query->where('lead_status', $request->status);
        }
        if ($request->has('quality') && $request->quality) {
            $query->where('lead_quality', $request->quality);
        }

        $leads = $query->latest()->paginate(15);
        
        // Metrics - ONLY for this marketing member's leads
        $totalCount = Patient::where('assigned_by', $userId)->count();
        $hotCount = Patient::where('assigned_by', $userId)->where('lead_quality', 'hot')->count();
        $newCount = Patient::where('assigned_by', $userId)->where('lead_status', 'new')->count();
        $assignedCount = Patient::where('assigned_by', $userId)->where('lead_status', 'assigned')->count();
        $convertedCount = Patient::where('assigned_by', $userId)->where('lead_status', 'converted')->count();

        // For assignment modal
        $companyDirect = $this->getCompanyDirectRepresentative();

        $representatives = Representative::with('user')->where('status', 'active')->get();
        if ($companyDirect && !$representatives->contains('id', $companyDirect->id)) {
            $representatives->push($companyDirect->load('user'));
        }

        $representatives->each(function ($rep) use ($companyDirect) {
            $rep->setAttribute(
                'display_name',
                ($companyDirect && $rep->id === $companyDirect->id)
                    ? 'Company Direct - ' . ($rep->country ?? 'India')
                    : (($rep->user->name ?? 'Representative') . ' - ' . ($rep->country ?? ''))
            );
        });

        return view('marketing.leads.index', compact('leads', 'representatives', 'totalCount', 'hotCount', 'newCount', 'assignedCount', 'convertedCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companyDirect = $this->getCompanyDirectRepresentative();

        $representatives = Representative::with('user')->where('status', 'active')->get();
        if ($companyDirect && !$representatives->contains('id', $companyDirect->id)) {
            $representatives->push($companyDirect->load('user'));
        }

        $representatives->each(function ($rep) use ($companyDirect) {
            $rep->setAttribute(
                'display_name',
                ($companyDirect && $rep->id === $companyDirect->id)
                    ? 'Company Direct - ' . ($rep->country ?? 'India')
                    : (($rep->user->name ?? 'Representative') . ' - ' . ($rep->country ?? ''))
            );
        });

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
     * Marketing members can only edit leads they created.
     */
    public function edit(Patient $lead)
    {
        // Verify ownership - marketing members can only edit their own leads
        if ($lead->assigned_by !== Auth::id()) {
            abort(403, 'You can only edit leads you have created.');
        }
        
        $representatives = Representative::with('user')->where('status', 'active')->get();
        return view('marketing.leads.edit', compact('lead', 'representatives'));
    }

    /**
     * Update the specified resource in storage.
     * Marketing members can only update leads they created.
     */
    public function update(Request $request, Patient $lead)
    {
        // Verify ownership
        if ($lead->assigned_by !== Auth::id()) {
            abort(403, 'You can only update leads you have created.');
        }
        
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
     * Marketing members can only assign leads they created.
     */
    public function assign(Request $request, Patient $lead)
    {
        // Verify ownership
        if ($lead->assigned_by !== Auth::id()) {
            abort(403, 'You can only assign leads you have created.');
        }
        
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
     * Marketing members can only delete leads they created.
     */
    public function destroy(Patient $lead)
    {
        // Verify ownership
        if ($lead->assigned_by !== Auth::id()) {
            abort(403, 'You can only delete leads you have created.');
        }
        
        // Delete related activities first
        LeadActivity::where('patient_id', $lead->id)->delete();
        
        $leadName = $lead->name;
        $lead->delete();

        return back()->with('success', "Lead '{$leadName}' deleted successfully.");
    }
}
