<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MarketingTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MarketingMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = User::where('role', 'marketing_member')
            ->latest()
            ->paginate(10);
            
        // Load current month's target for each member
        $currentMonth = now()->format('Y-m');
        $members->each(function ($member) use ($currentMonth) {
            $member->load(['marketingTargets' => function ($query) use ($currentMonth) {
                $query->where('month_year', $currentMonth);
            }]);
        });
            
        return view('admin.marketing_members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marketing_members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'marketing_member',
        ]);

        return redirect()->route('admin.marketing-members.index')
            ->with('success', 'Marketing Member created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $marketing_member)
    {
        if ($marketing_member->role !== 'marketing_member') {
            abort(404);
        }
        return view('admin.marketing_members.edit', compact('marketing_member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $marketing_member)
    {
        if ($marketing_member->role !== 'marketing_member') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($marketing_member->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $marketing_member->name = $validated['name'];
        $marketing_member->email = $validated['email'];
        
        if ($request->filled('password')) {
            $marketing_member->password = Hash::make($validated['password']);
        }

        $marketing_member->save();

        return redirect()->route('admin.marketing-members.index')
            ->with('success', 'Marketing Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $marketing_member)
    {
        if ($marketing_member->role !== 'marketing_member') {
            abort(403);
        }

        $marketing_member->delete();

        return redirect()->route('admin.marketing-members.index')
            ->with('success', 'Marketing Member deleted successfully.');
    }

    /**
     * Show the form for setting a target.
     */
    public function setTarget(User $user)
    {
        if ($user->role !== 'marketing_member') {
            abort(404);
        }

        $currentMonth = now()->format('Y-m');
        $target = MarketingTarget::firstOrNew(
            ['user_id' => $user->id, 'month_year' => $currentMonth]
        );

        return view('admin.marketing_members.set_target', compact('user', 'target', 'currentMonth'));
    }

    /**
     * Store the target.
     */
    public function storeTarget(Request $request, User $user)
    {
        if ($user->role !== 'marketing_member') {
            abort(404);
        }

        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
            'leads_assigned_target' => 'required|integer|min:0',
        ]);

        MarketingTarget::updateOrCreate(
            ['user_id' => $user->id, 'month_year' => $validated['month_year']],
            ['leads_assigned_target' => $validated['leads_assigned_target']]
        );

        return redirect()->route('admin.marketing-members.index')
            ->with('success', 'Monthly target set successfully.');
    }
}
