<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $representative = $user->representative;

        if (!$representative) {
            return redirect()->route('login')
                ->with('error', 'Representative profile not found.');
        }

        // Statistics
        $stats = [
            'total_patients' => $representative->patients()->count(),
            'total_orders' => $representative->orders()->count(),
            'active_orders' => $representative->orders()->active()->count(),
        ];

        // Upcoming renewals (next 30 days)
        $upcomingRenewals = Order::with(['patient', 'medicine'])
            ->where('representative_id', $representative->id)
            ->upcomingRenewals(30)
            ->orderBy('expected_renewal_date', 'asc')
            ->limit(10)
            ->get();

        // Overdue renewals
        $overdueRenewals = Order::with(['patient', 'medicine'])
            ->where('representative_id', $representative->id)
            ->overdue()
            ->orderBy('expected_renewal_date', 'asc')
            ->get();

        // Recent orders
        $recentOrders = Order::with(['patient', 'medicine'])
            ->where('representative_id', $representative->id)
            ->latest()
            ->limit(5)
            ->get();

        // Pending Leads
        $pendingLeads = Patient::where('representative_id', $representative->id)
            ->whereIn('lead_status', ['new', 'assigned', 'contacted', 'negotiating'])
            ->with('latestActivity')
            ->latest() // Pending usually means newest first or urgency? Latest assigned is good.
            ->limit(10)
            ->get();

        return view('representative.dashboard', compact(
            'representative',
            'stats',
            'upcomingRenewals',
            'overdueRenewals',
            'recentOrders',
            'pendingLeads'
        ));
    }
}
