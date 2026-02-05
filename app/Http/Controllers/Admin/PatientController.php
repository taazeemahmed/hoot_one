<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Representative;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private function getCompanyDirectRepresentative(): Representative
    {
        $user = auth()->user();

        $existing = Representative::where('user_id', $user->id)->first();
        if ($existing) {
            if ($existing->phone === 'N/A' && ($existing->country === 'Company Direct' || empty($existing->country))) {
                $existing->update(['country' => 'India']);
            }
            return $existing;
        }

        return Representative::create([
            'user_id' => $user->id,
            'country' => 'India',
            'country_code' => null,
            'region' => null,
            'phone' => 'N/A',
            'address' => null,
            'status' => 'active',
        ]);
    }

    public function index(Request $request)
    {
        $query = Patient::with(['representative.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by country
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        // Filter by representative
        if ($request->has('representative_id') && $request->representative_id) {
            $query->where('representative_id', $request->representative_id);
        }

        $patients = $query->withCount('orders')->latest()->paginate(10);
        $countries = Patient::distinct()->pluck('country');
        $representatives = Representative::with('user')->active()->get();

        return view('admin.patients.index', compact('patients', 'countries', 'representatives'));
    }

    public function create()
    {
        $representatives = Representative::with('user')->active()->get();
        return view('admin.patients.create', compact('representatives'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'representative_id' => 'nullable|exists:representatives,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'country' => 'required|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['representative_id'])) {
            $validated['representative_id'] = $this->getCompanyDirectRepresentative()->id;
        }

        Patient::create($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['representative.user', 'orders.medicine']);
        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $representatives = Representative::with('user')->active()->get();
        return view('admin.patients.edit', compact('patient', 'representatives'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'representative_id' => 'nullable|exists:representatives,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'country' => 'required|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['representative_id'])) {
            $validated['representative_id'] = $this->getCompanyDirectRepresentative()->id;
        }

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
