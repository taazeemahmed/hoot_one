<?php

namespace App\Http\Controllers;

use App\Models\LeadActivity;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|in:call,whatsapp,assignment,note',
            'result' => 'nullable|in:converted,follow_up,not_interested,not_reachable,assigned,info',
            'follow_up_at' => 'required_if:result,follow_up|nullable|date',
            'notes' => 'nullable|string'
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);

        // Security check: Reps can only log for their leads, Marketing for all
        if (Auth::user()->role === 'representative' && $patient->representative_id !== Auth::user()->representative->id) {
            abort(403, 'You do not own this lead.');
        }

        LeadActivity::create([
            'patient_id' => $validated['patient_id'],
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'result' => $validated['result'] ?? null,
            'follow_up_at' => $validated['follow_up_at'],
            'notes' => $validated['notes'],
        ]);

        // Auto-update status based on result
        if ($validated['result'] === 'converted') {
            $patient->update(['lead_status' => 'converted']);
            return back()->with('success', 'Lead converted! Please create an order now.'); // UI should handle directive
        } elseif ($validated['result'] === 'follow_up') {
            $patient->update(['lead_status' => 'follow_up']);
        } elseif ($validated['result'] === 'not_interested') {
            $patient->update(['lead_status' => 'not_interested']);
        }

        return back()->with('success', 'Activity logged successfully.');
    }
}
