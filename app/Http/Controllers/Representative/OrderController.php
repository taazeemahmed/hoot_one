<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $representative = auth()->user()->representative;
        
        $query = Order::with(['patient', 'medicine'])
            ->where('representative_id', $representative->id);

        // Search by patient
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by renewal date range
        if ($request->has('renewal_filter')) {
            $filter = $request->renewal_filter;
            if ($filter === 'overdue') {
                $query->overdue();
            } elseif ($filter === 'week') {
                $query->upcomingRenewals(7);
            } elseif ($filter === 'month') {
                $query->upcomingRenewals(30);
            }
        }

        $orders = $query->latest()->paginate(10);

        return view('representative.orders.index', compact('orders'));
    }

    public function create()
    {
        $representative = auth()->user()->representative;
        $patients = Patient::where('representative_id', $representative->id)->get();
        $medicines = Medicine::active()->get();
        
        // Define standard available codes
        $allCodes = [
            ['code' => '+91', 'label' => '+91 (IN)', 'countries' => ['India', 'IN']],
            ['code' => '+1', 'label' => '+1 (US)', 'countries' => ['USA', 'United States', 'US']],
            ['code' => '+44', 'label' => '+44 (UK)', 'countries' => ['United Kingdom', 'UK']],
            ['code' => '+971', 'label' => '+971 (UAE)', 'countries' => ['United Arab Emirates', 'UAE']],
        ];

        $countryCodes = [];
        $selectedCountryCode = null;

        // Logic 1: Check if Representative has a specific country_code assigned by Super Admin
        if (!empty($representative->country_code)) {
            $selectedCountryCode = $representative->country_code;
            
            // Check if this code exists in our standard list
            $foundInList = false;
            foreach ($allCodes as $item) {
                if ($item['code'] === $selectedCountryCode) {
                    $countryCodes[] = $item;
                    $foundInList = true;
                    break;
                }
            }

            // If the assigned code is NOT in our standard list/array (e.g. +233 for Ghana), 
            // we must create a dynamic custom entry so it appears in the dropdown.
            if (!$foundInList) {
                $countryCodes[] = [
                    'code' => $selectedCountryCode,
                    'label' => $selectedCountryCode . ' (' . ($representative->country ?? 'Custom') . ')',
                    'countries' => [$representative->country]
                ];
            }
        
        } else {
            // Logic 2: Fallback to guessing based on Country Name (Old Logic)
            foreach ($allCodes as $item) {
                foreach ($item['countries'] as $c) {
                    if (strcasecmp($representative->country, $c) === 0) {
                        $countryCodes[] = $item;
                        $selectedCountryCode = $item['code'];
                        break 2; 
                    }
                }
            }
        }

        // Final Fallback: If nothing matched/set, show all standard codes and default to +91
        if (empty($countryCodes)) {
            $countryCodes = $allCodes;
            $selectedCountryCode = '+91'; 
        }

        return view('representative.orders.create', compact('patients', 'medicines', 'representative', 'countryCodes', 'selectedCountryCode'));
    }

    public function store(Request $request)
    {
        $representative = auth()->user()->representative;
        
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'packs_ordered' => 'required|integer|min:1',
            'treatment_start_date' => 'required|date',
            'notes' => 'nullable|string',
            'create_new_patient' => 'sometimes|boolean',
            'patient_id' => 'required_without:create_new_patient|nullable|exists:patients,id',
            'new_patient_name' => 'required_with:create_new_patient|nullable|string|max:255',
            'new_patient_phone' => 'required_with:create_new_patient|nullable|string|max:20',
            'new_patient_country_code' => 'required_with:create_new_patient|nullable|string|max:5',
        ]);

        if ($request->boolean('create_new_patient')) {
            // Merge phone
            $fullPhone = $request->new_patient_country_code . ltrim($request->new_patient_phone, '0');

            // Create new patient
            $patient = Patient::create([
                'representative_id' => $representative->id,
                'name' => $request->new_patient_name,
                'phone' => $fullPhone,
                'email' => $request->new_patient_email ?? null,
                'country' => $request->new_patient_country ?? $representative->country,
                'address' => $request->new_patient_address ?? null,
                'notes' => 'Created instantly from Order page',
            ]);
        } else {
            // Verify patient belongs to this representative
            $patient = Patient::where('id', $request->patient_id)
                ->where('representative_id', $representative->id)
                ->firstOrFail();
        }

        $medicine = Medicine::findOrFail($request->medicine_id);

        // Calculate expected renewal date
        $renewalDate = Order::calculateRenewalDate(
            $request->treatment_start_date,
            $request->packs_ordered,
            $medicine->pack_duration_days
        );

        Order::create([
            'patient_id' => $patient->id,
            'medicine_id' => $request->medicine_id,
            'representative_id' => $representative->id,
            'packs_ordered' => $request->packs_ordered,
            'treatment_start_date' => $request->treatment_start_date,
            'expected_renewal_date' => $renewalDate,
            'notes' => $request->notes ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('representative.orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['patient', 'medicine']);
        
        return view('representative.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorizeOrder($order);
        $representative = auth()->user()->representative;
        $patients = Patient::where('representative_id', $representative->id)->get();
        $medicines = Medicine::active()->get();
        
        return view('representative.orders.edit', compact('order', 'patients', 'medicines'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorizeOrder($order);
        $representative = auth()->user()->representative;
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicine_id' => 'required|exists:medicines,id',
            'packs_ordered' => 'required|integer|min:1',
            'treatment_start_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        // Verify patient belongs to this representative
        $patient = Patient::where('id', $validated['patient_id'])
            ->where('representative_id', $representative->id)
            ->firstOrFail();

        $medicine = Medicine::findOrFail($validated['medicine_id']);

        // Recalculate expected renewal date
        $renewalDate = Order::calculateRenewalDate(
            $validated['treatment_start_date'],
            $validated['packs_ordered'],
            $medicine->pack_duration_days
        );

        $order->update([
            'patient_id' => $validated['patient_id'],
            'medicine_id' => $validated['medicine_id'],
            'packs_ordered' => $validated['packs_ordered'],
            'treatment_start_date' => $validated['treatment_start_date'],
            'expected_renewal_date' => $renewalDate,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('representative.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->authorizeOrder($order);
        $order->delete();

        return redirect()->route('representative.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Authorize that the order belongs to the current representative
     */
    private function authorizeOrder(Order $order)
    {
        $representative = auth()->user()->representative;
        
        if ($order->representative_id !== $representative->id) {
            abort(403, 'Unauthorized access to this order.');
        }
    }
}
