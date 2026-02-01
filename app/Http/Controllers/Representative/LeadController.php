<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeadController extends Controller
{
    public function index()
    {
        $representative = auth()->user()->representative;

        if (!$representative) {
            abort(403, 'User is not a representative.');
        }

        // Fetch patients that are in 'assigned' or 'contacted' status
        $leads = Patient::where('representative_id', $representative->id)
            ->whereIn('lead_status', ['new', 'assigned', 'contacted', 'negotiating'])
            ->with(['latestActivity'])
            ->latest('assigned_at')
            ->paginate(15);
            
        $medicines = Medicine::active()->get();

        return view('representative.leads.index', compact('leads', 'medicines'));
    }

    public function storeActivity(Request $request, Patient $lead)
    {
        // Authorize
        if ($lead->representative_id !== auth()->user()->representative->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'type' => 'required|in:call,whatsapp,note',
            'result' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status_update' => 'nullable|in:contacted,negotiating,lost,converted',
            'lead_quality' => 'nullable|in:hot,warm,cold',
            // Order details if converting
            'medicine_id' => 'required_if:status_update,converted|nullable|exists:medicines,id',
            'packs_ordered' => 'required_if:status_update,converted|nullable|integer|min:1',
        ]);

        LeadActivity::create([
            'patient_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'result' => $validated['result'],
            'notes' => $validated['notes'],
        ]);

        // Update Lead Quality if provided
        if (!empty($validated['lead_quality'])) {
            $lead->update(['lead_quality' => $validated['lead_quality']]);
        }

        // Update lead status if requested
        if (!empty($validated['status_update'])) {
            $lead->update(['lead_status' => $validated['status_update']]);

            // Handle Conversion to Patient (Create Order)
            if ($validated['status_update'] === 'converted') {
                $medicine = Medicine::find($validated['medicine_id']);
                if ($medicine) {
                     // Default start date to today if not provided (keeping it simple for modal)
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
                        'notes' => 'Initial order on conversion',
                        'status' => 'active',
                    ]);
                }
            }

        } elseif ($lead->lead_status === 'assigned' || $lead->lead_status === 'new') {
            // Auto-move to 'contacted' on first activity
            $lead->update(['lead_status' => 'contacted']);
        }

        return back()->with('success', 'Activity logged successfully.');
    }
    public function edit(Patient $lead)
    {
        // Authorize
        if ($lead->representative_id !== auth()->user()->representative->id) {
            abort(403, 'Unauthorized.');
        }

        $medicines = Medicine::active()->get();
        return view('representative.leads.edit', compact('lead', 'medicines'));
    }

    public function update(Request $request, Patient $lead)
    {
        // Authorize
        if ($lead->representative_id !== auth()->user()->representative->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'lead_quality' => 'nullable|in:hot,warm,cold',
            'lead_status' => 'required|in:new,assigned,contacted,negotiating,lost,converted',
            
            // Order details if converting
            'medicine_id' => 'required_if:lead_status,converted|nullable|exists:medicines,id',
            'packs_ordered' => 'required_if:lead_status,converted|nullable|integer|min:1',
        ]);

        $lead->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'], 
            'country' => $validated['country'],
            'notes' => $validated['notes'],
            'lead_quality' => $validated['lead_quality'],
            'lead_status' => $validated['lead_status'],
        ]);

        // Handle Conversion
        if ($validated['lead_status'] === 'converted') {
             $medicine = Medicine::find($request->medicine_id);
             if ($medicine) {
                // Default start date to today
                $startDate = Carbon::today();
                $renewalDate = Order::calculateRenewalDate(
                    $startDate,
                    $request->packs_ordered,
                    $medicine->pack_duration_days
                );

                Order::create([
                    'patient_id' => $lead->id,
                    'medicine_id' => $medicine->id,
                    'representative_id' => $lead->representative_id,
                    'packs_ordered' => $request->packs_ordered,
                    'treatment_start_date' => $startDate,
                    'expected_renewal_date' => $renewalDate,
                    'notes' => 'Initial order on conversion (via Edit)',
                    'status' => 'active',
                ]);
            }
        }

        return redirect()->route('representative.leads.index')->with('success', 'Lead updated successfully.');
    }
}
