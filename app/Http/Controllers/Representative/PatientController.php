<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $representative = auth()->user()->representative;
        
        $query = Patient::where('representative_id', $representative->id);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $query->withCount('orders')->latest()->paginate(10);

        return view('representative.patients.index', compact('patients'));
    }

    public function create()
    {
        $representative = auth()->user()->representative;
        return view('representative.patients.create', compact('representative'));
    }

    public function store(Request $request)
    {
        $representative = auth()->user()->representative;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
            'country' => 'required|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Merge phone
        $fullPhone = $request->country_code . ltrim($request->phone, '0');

        Patient::create([
            'representative_id' => $representative->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $fullPhone,
            'country' => $request->country,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('representative.patients.index')
            ->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient)
    {
        $this->authorizePatient($patient);
        $patient->load('orders.medicine');
        
        return view('representative.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $this->authorizePatient($patient);
        return view('representative.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorizePatient($patient);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'country' => 'required|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('representative.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $this->authorizePatient($patient);
        $patient->delete();

        return redirect()->route('representative.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    /**
     * Authorize that the patient belongs to the current representative
     */
    private function authorizePatient(Patient $patient)
    {
        $representative = auth()->user()->representative;
        
        if ($patient->representative_id !== $representative->id) {
            abort(403, 'Unauthorized access to this patient.');
        }
    }
}
