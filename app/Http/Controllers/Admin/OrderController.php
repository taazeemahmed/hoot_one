<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Representative;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['patient', 'medicine', 'representative.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by representative
        if ($request->filled('representative_id')) {
            $query->where('representative_id', $request->representative_id);
        }

        // Filter by medicine
        if ($request->filled('medicine_id')) {
            $query->where('medicine_id', $request->medicine_id);
        }

        // Filter by Country (via Representative)
        if ($request->filled('country')) {
            $country = $request->country;
            $query->whereHas('representative', function($q) use ($country) {
                $q->where('country', $country);
            });
        }

        // Filter by renewal date range
        if ($request->filled('renewal_filter')) {
            $filter = $request->renewal_filter;
            if ($filter === 'overdue') {
                $query->overdue();
            } elseif ($filter === 'week') {
                $query->upcomingRenewals(7);
            } elseif ($filter === 'month') {
                $query->upcomingRenewals(30);
            }
        }

        // CSV Export
        if ($request->has('export') && $request->export === 'true') {
            return $this->exportCsv($query->get());
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        
        $representatives = Representative::with('user')->active()->get();
        $medicines = Medicine::active()->orderBy('name')->get();
        $countries = Representative::select('country')->distinct()->orderBy('country')->pluck('country');

        return view('admin.orders.index', compact('orders', 'representatives', 'medicines', 'countries'));
    }

    private function exportCsv($orders)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_export_' . now()->format('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, [
                'Order ID', 
                'Patient Name', 
                'Patient Phone', 
                'Patient Email',
                'Country',
                'Medicine', 
                'Packs', 
                'Start Date', 
                'Renewal Date', 
                'Status', 
                'Representative',
                'Notes'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id ?? '',
                    $order->patient->name ?? '',
                    $order->patient->phone ?? '',
                    $order->patient->email ?? '',
                    $order->representative->country ?? '',
                    $order->medicine->name ?? '',
                    $order->packs_ordered ?? '',
                    $order->treatment_start_date ? $order->treatment_start_date->format('Y-m-d') : '',
                    $order->expected_renewal_date ? $order->expected_renewal_date->format('Y-m-d') : '',
                    ucfirst($order->status),
                    $order->representative->user->name ?? '',
                    $order->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function create()
    {
        $patients = Patient::with('representative.user')->get();
        $medicines = Medicine::active()->get();
        $representatives = Representative::with('user')->active()->get();
        
        return view('admin.orders.create', compact('patients', 'medicines', 'representatives'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicine_id' => 'required|exists:medicines,id',
            'packs_ordered' => 'required|integer|min:1',
            'treatment_start_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);
        $medicine = Medicine::findOrFail($validated['medicine_id']);

        // Calculate expected renewal date
        $renewalDate = Order::calculateRenewalDate(
            $validated['treatment_start_date'],
            $validated['packs_ordered'],
            $medicine->pack_duration_days
        );

        Order::create([
            'patient_id' => $validated['patient_id'],
            'medicine_id' => $validated['medicine_id'],
            'representative_id' => $patient->representative_id,
            'packs_ordered' => $validated['packs_ordered'],
            'treatment_start_date' => $validated['treatment_start_date'],
            'expected_renewal_date' => $renewalDate,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['patient', 'medicine', 'representative.user']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['patient', 'medicine']);
        $patients = Patient::with('representative.user')->get();
        $medicines = Medicine::active()->get();
        
        return view('admin.orders.edit', compact('order', 'patients', 'medicines'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicine_id' => 'required|exists:medicines,id',
            'packs_ordered' => 'required|integer|min:1',
            'treatment_start_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);
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
            'representative_id' => $patient->representative_id,
            'packs_ordered' => $validated['packs_ordered'],
            'treatment_start_date' => $validated['treatment_start_date'],
            'expected_renewal_date' => $renewalDate,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
