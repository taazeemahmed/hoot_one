<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Order;
use App\Models\Representative;
use App\Models\User;
use App\Models\LeadActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Main analytics dashboard.
     */
    public function index()
    {
        $totalLeads = Patient::count();
        $convertedPatients = Patient::where('lead_status', 'converted')->count();
        $totalOrders = Order::count();
            
        // Get last month's leads for growth calculation
        $lastMonthLeads = Patient::where('created_at', '<', now()->startOfMonth())
            ->where('created_at', '>=', now()->subMonths(2)->startOfMonth())
            ->count();
        $thisMonthLeads = Patient::where('created_at', '>=', now()->startOfMonth())->count();
        $leadsGrowth = $lastMonthLeads > 0 ? round((($thisMonthLeads - $lastMonthLeads) / $lastMonthLeads) * 100, 1) : 0;

        // Overall KPIs
        $kpis = [
            'totalLeads' => $totalLeads,
            'convertedPatients' => $convertedPatients,
            'conversionRate' => $totalLeads > 0 ? round(($convertedPatients / $totalLeads) * 100, 1) : 0,
            'totalOrders' => $totalOrders,
            'leadsGrowth' => $leadsGrowth,
        ];

        // Monthly trends (last 6 months)
        $monthlyTrends = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $total = Patient::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $converted = Patient::where('lead_status', 'converted')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $monthlyTrends->push([
                'month' => $date->format('M Y'),
                'total' => $total,
                'converted' => $converted,
            ]);
        }

        // Lead funnel as array of objects for the view
        $funnel = collect([
            ['status' => 'new', 'count' => Patient::where('lead_status', 'new')->count()],
            ['status' => 'assigned', 'count' => Patient::where('lead_status', 'assigned')->count()],
            ['status' => 'contacted', 'count' => Patient::where('lead_status', 'contacted')->count()],
            ['status' => 'negotiating', 'count' => Patient::where('lead_status', 'negotiating')->count()],
            ['status' => 'converted', 'count' => Patient::where('lead_status', 'converted')->count()],
            ['status' => 'lost', 'count' => Patient::whereIn('lead_status', ['lost', 'not_interested'])->count()],
        ]);

        // Conversion by source
        $conversionBySource = Patient::query()
            ->selectRaw(
                "source as lead_source, COUNT(*) as total, " .
                "SUM(CASE WHEN lead_status = 'converted' THEN 1 ELSE 0 END) as converted, " .
                "SUM(CASE WHEN lead_status IN ('lost', 'not_interested') THEN 1 ELSE 0 END) as lost"
            )
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        // Conversion by country
        $conversionByCountry = Patient::select(
                'country',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN lead_status = 'converted' THEN 1 ELSE 0 END) as converted"),
                DB::raw("SUM(CASE WHEN lead_status IN ('lost', 'not_interested') THEN 1 ELSE 0 END) as lost")
            )
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'kpis',
            'monthlyTrends',
            'funnel',
            'conversionBySource',
            'conversionByCountry'
        ));
    }

    /**
     * Detailed leads analytics.
     */
    public function leads(Request $request)
    {
        $period = $request->get('period', 30);
        $startDate = now()->subDays($period);

        // Status breakdown counts
        $stats = [
            'new' => Patient::where('lead_status', 'new')->where('created_at', '>=', $startDate)->count(),
            'assigned' => Patient::where('lead_status', 'assigned')->where('created_at', '>=', $startDate)->count(),
            'contacted' => Patient::where('lead_status', 'contacted')->where('created_at', '>=', $startDate)->count(),
            'negotiating' => Patient::where('lead_status', 'negotiating')->where('created_at', '>=', $startDate)->count(),
            'converted' => Patient::where('lead_status', 'converted')->where('created_at', '>=', $startDate)->count(),
            'lost' => Patient::whereIn('lead_status', ['lost', 'not_interested'])->where('created_at', '>=', $startDate)->count(),
        ];

        // Conversion by source
        $conversionBySource = Patient::query()
            ->selectRaw(
                "source as lead_source, COUNT(*) as total, " .
                "SUM(CASE WHEN lead_status = 'converted' THEN 1 ELSE 0 END) as converted, " .
                "SUM(CASE WHEN lead_status IN ('lost', 'not_interested') THEN 1 ELSE 0 END) as lost"
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        // Daily lead trend
        $dailyTrends = Patient::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.leads', compact(
            'stats',
            'conversionBySource',
            'dailyTrends',
            'period'
        ));
    }

    /**
     * Representative performance analytics.
     */
    public function representatives(Request $request)
    {
        $representatives = Representative::with('user')
            ->get()
            ->map(function($rep) {
                // Basic patient metrics
                $totalLeads = Patient::where('representative_id', $rep->id)->count();
                $converted = Patient::where('representative_id', $rep->id)->where('lead_status', 'converted')->count();
                $lost = Patient::where('representative_id', $rep->id)->whereIn('lead_status', ['lost', 'not_interested'])->count();
                
                return (object)[
                    'id' => $rep->id,
                    'name' => $rep->user->name ?? $rep->name,
                    'email' => $rep->user->email ?? '',
                    'country' => $rep->country,
                    'total_leads' => $totalLeads,
                    'converted' => $converted,
                    'lost' => $lost,
                ];
            })
            ->sortByDesc(function($rep) {
                return $rep->total_leads > 0 ? ($rep->converted / $rep->total_leads) : 0;
            })
            ->values();

        return view('admin.analytics.representatives', compact('representatives'));
    }

    /**
     * Marketing team performance analytics.
     */
    public function marketing(Request $request)
    {
        $marketingMembers = User::where('role', 'marketing_member')
            ->get()
            ->map(function($member) {
                // Get leads added by this member (all time for ranking)
                $totalLeads = Patient::where('assigned_by', $member->id)->count();
                $converted = Patient::where('assigned_by', $member->id)->where('lead_status', 'converted')->count();
                $lost = Patient::where('assigned_by', $member->id)->whereIn('lead_status', ['lost', 'not_interested'])->count();
                $unassigned = Patient::where('assigned_by', $member->id)->whereNull('representative_id')->count();
                
                return (object)[
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'total_leads' => $totalLeads,
                    'converted' => $converted,
                    'lost' => $lost,
                    'unassigned' => $unassigned,
                ];
            })
            ->sortByDesc('total_leads')
            ->values();

        return view('admin.analytics.marketing', compact('marketingMembers'));
    }

    /**
     * Helper: Calculate overall conversion rate.
     */
    private function calculateOverallConversionRate()
    {
        $total = Patient::count();
        $converted = Patient::where('lead_status', 'converted')->count();
        
        return $total > 0 ? round(($converted / $total) * 100, 1) : 0;
    }

    /**
     * Helper: Get monthly trends.
     */
    private function getMonthlyTrends($months)
    {
        $trends = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $leads = Patient::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $converted = Patient::where('lead_status', 'converted')
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->count();
            $orders = Order::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            
            $trends[] = [
                'month' => $date->format('M Y'),
                'month_short' => $date->format('M'),
                'leads' => $leads,
                'converted' => $converted,
                'orders' => $orders,
            ];
        }
        
        return $trends;
    }

    /**
     * Helper: Get start date based on period.
     */
    private function getStartDate($period)
    {
        return match($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }

    /**
     * Helper: Get average conversion time.
     */
    private function getAverageConversionTime($startDate)
    {
        // This would require tracking conversion timestamp separately
        // For now, return a placeholder
        return 'N/A';
    }

    /**
     * Helper: Get representative performance summary.
     */
    private function getRepresentativePerformanceSummary()
    {
        return Representative::with('user')
            ->withCount([
                'patients as total_patients',
                'orders as total_orders',
            ])
            ->get()
            ->sortByDesc('total_patients')
            ->take(5);
    }

    /**
     * Helper: Get marketing performance summary.
     */
    private function getMarketingPerformanceSummary()
    {
        return User::where('role', 'marketing_member')
            ->get()
            ->map(function($member) {
                $member->leads_added = Patient::where('assigned_by', $member->id)
                    ->whereMonth('created_at', now()->month)
                    ->count();
                return $member;
            })
            ->sortByDesc('leads_added')
            ->take(5);
    }

    /**
     * Helper: Get rep performance trend.
     */
    private function getRepPerformanceTrend($startDate)
    {
        // Implementation for detailed trend data
        return [];
    }
}
