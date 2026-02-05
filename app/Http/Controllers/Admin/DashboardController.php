<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\Patient;
use App\Models\Order;
use App\Models\Medicine;
use App\Models\User;
use App\Models\WhatsappLog;
use App\Models\LeadActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // ============================================
        // PRIORITY 1: CRITICAL & TIME-SENSITIVE
        // ============================================
        
        // Overdue renewals with days overdue
        $overdueRenewals = Order::with(['patient', 'medicine', 'representative.user'])
            ->overdue()
            ->orderBy('expected_renewal_date', 'asc')
            ->get()
            ->map(function($order) use ($today) {
                $order->days_overdue = $today->diffInDays($order->expected_renewal_date);
                return $order;
            });

        // Critical renewals due within 7 days
        $urgentRenewals = Order::with(['patient', 'medicine', 'representative.user'])
            ->where('status', 'active')
            ->whereBetween('expected_renewal_date', [$today, $today->copy()->addDays(7)])
            ->orderBy('expected_renewal_date', 'asc')
            ->get();

        // Orders pending too long (>3 days)
        $stuckOrders = Order::with(['patient', 'medicine', 'representative.user'])
            ->where('status', 'pending')
            ->where('created_at', '<', $today->copy()->subDays(3))
            ->orderBy('created_at', 'asc')
            ->get();

        // WhatsApp failures (last 7 days)
        $whatsappFailures = WhatsappLog::with(['patient', 'order'])
            ->where('status', '!=', 'sent')
            ->where('created_at', '>=', $today->copy()->subDays(7))
            ->latest()
            ->limit(10)
            ->get();

        // Unassigned leads
        $unassignedLeads = Patient::whereNull('representative_id')
            ->whereIn('lead_status', ['new', 'assigned'])
            ->latest()
            ->limit(10)
            ->get();

        // Leads with missed follow-ups
        $missedFollowups = LeadActivity::with(['patient', 'user'])
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '<', $today)
            ->whereHas('patient', function($q) {
                $q->whereNotIn('lead_status', ['converted', 'lost', 'not_interested']);
            })
            ->orderBy('follow_up_at', 'asc')
            ->limit(10)
            ->get();

        // Build critical alerts list
        $criticalAlerts = collect();
        
        foreach ($overdueRenewals->take(5) as $order) {
            $criticalAlerts->push([
                'type' => 'overdue_renewal',
                'severity' => 'critical',
                'title' => $order->patient->name ?? 'Unknown',
                'subtitle' => ($order->medicine->name ?? 'Medicine') . ' · ' . ($order->representative->user->name ?? 'Unassigned'),
                'detail' => $order->days_overdue . ' days overdue',
                'phone' => $order->patient->phone ?? null,
                'patient_id' => $order->patient_id,
                'order_id' => $order->id,
            ]);
        }

        foreach ($stuckOrders->take(3) as $order) {
            $daysStuck = $today->diffInDays($order->created_at);
            $criticalAlerts->push([
                'type' => 'stuck_order',
                'severity' => 'warning',
                'title' => $order->patient->name ?? 'Unknown',
                'subtitle' => 'Order pending for ' . $daysStuck . ' days',
                'detail' => $order->medicine->name ?? 'Medicine',
                'phone' => $order->patient->phone ?? null,
                'patient_id' => $order->patient_id,
                'order_id' => $order->id,
            ]);
        }

        foreach ($unassignedLeads->take(3) as $lead) {
            $criticalAlerts->push([
                'type' => 'unassigned_lead',
                'severity' => 'warning',
                'title' => $lead->name,
                'subtitle' => 'From ' . ($lead->source ?? 'unknown source') . ' · ' . $lead->country,
                'detail' => 'Needs assignment',
                'phone' => $lead->phone,
                'patient_id' => $lead->id,
            ]);
        }

        // ============================================
        // PRIORITY 2: BUSINESS HEALTH SNAPSHOT
        // ============================================

        // Total patients with trend
        $totalPatients = Patient::count();
        $patientsThisMonth = Patient::where('created_at', '>=', $thisMonth)->count();
        $patientsLastMonth = Patient::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $patientsGrowth = $patientsLastMonth > 0 
            ? round((($patientsThisMonth - $patientsLastMonth) / $patientsLastMonth) * 100, 1) 
            : ($patientsThisMonth > 0 ? 100 : 0);

        // Converted patients (those with orders)
        $convertedPatients = Patient::where('lead_status', 'converted')->count();

        // Active orders
        $activeOrders = Order::where('status', 'active')->count();
        $ordersThisMonth = Order::where('created_at', '>=', $thisMonth)->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $ordersGrowth = $ordersLastMonth > 0 
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 1) 
            : ($ordersThisMonth > 0 ? 100 : 0);

        // Renewals due this week / month
        $renewalsDueThisWeek = Order::where('status', 'active')
            ->whereBetween('expected_renewal_date', [$today, $today->copy()->addDays(7)])
            ->count();
        $renewalsDueThisMonth = Order::where('status', 'active')
            ->whereBetween('expected_renewal_date', [$today, $today->copy()->addDays(30)])
            ->count();

        // Lead to patient conversion rate
        $totalLeads = Patient::count();
        $conversionRate = $totalLeads > 0 
            ? round(($convertedPatients / $totalLeads) * 100, 1) 
            : 0;

        // KPIs
        $kpis = [
            'total_patients' => $totalPatients,
            'patients_this_month' => $patientsThisMonth,
            'patients_growth' => $patientsGrowth,
            'converted_patients' => $convertedPatients,
            'active_orders' => $activeOrders,
            'orders_this_month' => $ordersThisMonth,
            'orders_growth' => $ordersGrowth,
            'conversion_rate' => $conversionRate,
            'renewals_due_week' => $renewalsDueThisWeek,
            'renewals_due_month' => $renewalsDueThisMonth,
            'overdue_count' => $overdueRenewals->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        // ============================================
        // PRIORITY 3: PERFORMANCE & ACCOUNTABILITY
        // ============================================

        // Representative performance
        $repPerformance = Representative::with('user')
            ->withCount([
                'patients as total_leads',
                'patients as converted_leads' => function($q) {
                    $q->where('lead_status', 'converted');
                },
                'orders as active_orders' => function($q) {
                    $q->where('status', 'active');
                },
            ])
            ->get()
            ->map(function($rep) use ($overdueRenewals) {
                $repOverdue = $overdueRenewals->where('representative_id', $rep->id)->count();
                $convRate = $rep->total_leads > 0 
                    ? round(($rep->converted_leads / $rep->total_leads) * 100, 1) 
                    : 0;
                return [
                    'id' => $rep->id,
                    'name' => $rep->user->name ?? 'Unknown',
                    'country' => $rep->country,
                    'total_leads' => $rep->total_leads,
                    'converted' => $rep->converted_leads,
                    'conversion_rate' => $convRate,
                    'active_orders' => $rep->active_orders,
                    'overdue_count' => $repOverdue,
                    'status' => $rep->status,
                ];
            })
            ->sortByDesc('conversion_rate')
            ->values();

        // Marketing team performance
        $marketingPerformance = User::where('role', 'marketing_member')
            ->get()
            ->map(function($member) use ($thisMonth) {
                $leadsAdded = Patient::where('assigned_by', $member->id)->count();
                $leadsThisMonth = Patient::where('assigned_by', $member->id)
                    ->where('created_at', '>=', $thisMonth)
                    ->count();
                $leadsConverted = Patient::where('assigned_by', $member->id)
                    ->where('lead_status', 'converted')
                    ->count();
                $convRate = $leadsAdded > 0 ? round(($leadsConverted / $leadsAdded) * 100, 1) : 0;
                
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'total_leads' => $leadsAdded,
                    'leads_this_month' => $leadsThisMonth,
                    'converted' => $leadsConverted,
                    'conversion_rate' => $convRate,
                ];
            })
            ->sortByDesc('total_leads')
            ->values();

        // Country-wise performance
        $countryPerformance = Patient::select('country')
            ->selectRaw('COUNT(*) as total_leads')
            ->selectRaw("SUM(CASE WHEN lead_status = 'converted' THEN 1 ELSE 0 END) as converted")
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total_leads')
            ->limit(8)
            ->get()
            ->map(function($item) {
                $item->conversion_rate = $item->total_leads > 0 
                    ? round(($item->converted / $item->total_leads) * 100, 1) 
                    : 0;
                return $item;
            });

        // Medicine demand
        $medicineDemand = Medicine::withCount(['orders as total_orders'])
            ->withCount(['orders as active_orders' => function($q) {
                $q->where('status', 'active');
            }])
            ->where('status', 'active')
            ->orderByDesc('total_orders')
            ->limit(6)
            ->get();

        // ============================================
        // RENEWALS COMMAND DATA
        // ============================================

        // All renewals for command table (due within 30 days + overdue)
        $renewalsCommand = Order::with(['patient', 'medicine', 'representative.user'])
            ->where('status', 'active')
            ->where('expected_renewal_date', '<=', $today->copy()->addDays(30))
            ->orderBy('expected_renewal_date', 'asc')
            ->get()
            ->map(function($order) use ($today) {
                $days = $today->diffInDays($order->expected_renewal_date, false);
                $order->days_remaining = $days;
                $order->urgency = $days < 0 ? 'overdue' : ($days <= 3 ? 'critical' : ($days <= 7 ? 'urgent' : 'upcoming'));
                return $order;
            });

        // ============================================
        // WHATSAPP & SYSTEM HEALTH
        // ============================================

        $whatsappStats = [
            'total_sent_today' => WhatsappLog::whereDate('created_at', $today)->count(),
            'success_today' => WhatsappLog::whereDate('created_at', $today)->where('status', 'sent')->count(),
            'failed_today' => WhatsappLog::whereDate('created_at', $today)->where('status', '!=', 'sent')->count(),
            'failed_week' => WhatsappLog::where('created_at', '>=', $today->copy()->subDays(7))->where('status', '!=', 'sent')->count(),
            'last_sent' => WhatsappLog::where('status', 'sent')->latest()->first()?->created_at,
        ];

        // ============================================
        // EXECUTIVE SUMMARY INSIGHTS
        // ============================================

        $insights = [];
        
        if ($overdueRenewals->count() > 0) {
            $insights[] = "⚠️ {$overdueRenewals->count()} renewals overdue";
        }
        
        // Find lagging country
        $laggingCountry = $countryPerformance->where('conversion_rate', '<', 20)->first();
        if ($laggingCountry && $laggingCountry->total_leads >= 5) {
            $insights[] = "{$laggingCountry->country} conversion at {$laggingCountry->conversion_rate}%";
        }
        
        // Best performing country
        $topCountry = $countryPerformance->sortByDesc('conversion_rate')->first();
        if ($topCountry && $topCountry->conversion_rate > 50) {
            $insights[] = "{$topCountry->country} performing strongly at {$topCountry->conversion_rate}%";
        }

        if ($unassignedLeads->count() > 5) {
            $insights[] = "{$unassignedLeads->count()} leads need assignment";
        }

        if ($whatsappStats['failed_week'] > 10) {
            $insights[] = "{$whatsappStats['failed_week']} WhatsApp failures this week";
        }

        $executiveSummary = [
            'overdue_today' => $overdueRenewals->count(),
            'renewals_due_today' => Order::where('status', 'active')
                ->whereDate('expected_renewal_date', $today)
                ->count(),
            'orders_pending' => $kpis['pending_orders'],
            'whatsapp_issues' => $whatsappStats['failed_week'],
            'unassigned_leads' => $unassignedLeads->count(),
            'insight_text' => implode(' · ', array_slice($insights, 0, 3)),
        ];

        // Monthly trend for charts (last 6 months)
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            $monthlyTrend->push([
                'month' => $monthStart->format('M'),
                'patients' => Patient::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'orders' => Order::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'conversions' => Patient::where('lead_status', 'converted')
                    ->whereBetween('updated_at', [$monthStart, $monthEnd])
                    ->count(),
            ]);
        }

        return view('admin.dashboard', compact(
            'executiveSummary',
            'criticalAlerts',
            'kpis',
            'overdueRenewals',
            'urgentRenewals',
            'renewalsCommand',
            'repPerformance',
            'marketingPerformance',
            'countryPerformance',
            'medicineDemand',
            'whatsappStats',
            'monthlyTrend',
            'unassignedLeads',
            'missedFollowups'
        ));
    }
}
