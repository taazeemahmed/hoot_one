<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RepresentativeController extends Controller
{
    public function index(Request $request)
    {
        $query = Representative::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('country', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by country
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        $representatives = $query->latest()->paginate(10);
        $countries = Representative::distinct()->pluck('country');

        return view('admin.representatives.index', compact('representatives', 'countries'));
    }

    public function create()
    {
        return view('admin.representatives.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'country' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'region' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'representative',
        ]);

        // Create representative profile
        Representative::create([
            'user_id' => $user->id,
            'country' => $validated['country'],
            'country_code' => $validated['country_code'] ?? null,
            'region' => $validated['region'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.representatives.index')
            ->with('success', 'Representative created successfully.');
    }

    public function show(Representative $representative)
    {
        $representative->load(['user', 'patients', 'orders.medicine']);
        
        $stats = [
            'total_patients' => $representative->patients()->count(),
            'total_orders' => $representative->orders()->count(),
            'active_orders' => $representative->orders()->active()->count(),
        ];

        return view('admin.representatives.show', compact('representative', 'stats'));
    }

    public function edit(Representative $representative)
    {
        $representative->load('user');
        return view('admin.representatives.edit', compact('representative'));
    }

    public function update(Request $request, Representative $representative)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($representative->user_id)],
            'password' => 'nullable|min:8|confirmed',
            'country' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'region' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Update user
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        
        $representative->user->update($userData);

        // Update representative profile
        $representative->update([
            'country' => $validated['country'],
            'country_code' => $validated['country_code'] ?? null,
            'region' => $validated['region'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.representatives.index')
            ->with('success', 'Representative updated successfully.');
    }

    public function destroy(Representative $representative)
    {
        $user = $representative->user;
        $representative->delete();
        $user->delete();

        return redirect()->route('admin.representatives.index')
            ->with('success', 'Representative deleted successfully.');
    }
}
