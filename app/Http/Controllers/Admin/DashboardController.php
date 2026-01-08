<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\Patient;
use App\Models\Order;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_representatives' => Representative::count(),
            'active_representatives' => Representative::active()->count(),
            'total_patients' => Patient::count(),
            'total_orders' => Order::count(),
            'active_orders' => Order::active()->count(),
            'total_medicines' => Medicine::active()->count(),
        ];

        // Upcoming renewals (next 30 days)
        $upcomingRenewals = Order::with(['patient', 'medicine', 'representative.user'])
            ->upcomingRenewals(30)
            ->orderBy('expected_renewal_date', 'asc')
            ->limit(10)
            ->get();

        // Overdue renewals
        $overdueRenewals = Order::with(['patient', 'medicine', 'representative.user'])
            ->overdue()
            ->orderBy('expected_renewal_date', 'asc')
            ->limit(10)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['patient', 'medicine', 'representative.user'])
            ->latest()
            ->limit(5)
            ->get();

        // Orders by country
        $ordersByCountry = Patient::selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'upcomingRenewals',
            'overdueRenewals',
            'recentOrders',
            'ordersByCountry'
        ));
    }
}
