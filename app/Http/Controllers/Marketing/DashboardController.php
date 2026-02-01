<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\LeadActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Core Stats
        $stats = [
            'new_leads' => Patient::where('lead_status', 'new')->count(),
            'assigned_today' => Patient::whereDate('assigned_at', today())->count(),
            'hot_leads_unassigned' => Patient::where('lead_quality', 'hot')->whereNull('representative_id')->count(),
        ];

        // Leads needing assignment (Incoming stream)
        $incomingLeads = Patient::where('lead_status', 'new')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // My recent activities
        $recentActivities = LeadActivity::where('user_id', Auth::id())
            ->with(['patient'])
            ->latest()
            ->take(5)
            ->get();

        // Targets & Score
        $currentMonth = now()->format('Y-m');
        $target = \App\Models\MarketingTarget::where('user_id', Auth::id())
            ->where('month_year', $currentMonth)
            ->first();

        $myLeadsAddedCount = Patient::where('assigned_by', Auth::id())
            ->whereMonth('assigned_at', now()->month)
            ->whereYear('assigned_at', now()->year)
            ->count();
        
        $score = [
            'leads_assigned' => $myLeadsAddedCount,
            'target_assigned' => $target ? $target->leads_assigned_target : 0,
            'percentage' => ($target && $target->leads_assigned_target > 0) ? round(($myLeadsAddedCount / $target->leads_assigned_target) * 100) : 0
        ];

        // Chart Data: Assignment Velocity (Last 7 Days)
        $velocityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Patient::whereDate('assigned_at', $date)->count();
            $velocityData[] = [
                'day' => $date->format('D'),
                'count' => $count
            ];
        }

        // Chart Data: Lead Quality Distribution (All Time or Current Pool)
        $qualityData = [
            'hot' => Patient::where('lead_quality', 'hot')->count(),
            'warm' => Patient::where('lead_quality', 'warm')->count(),
            'cold' => Patient::whereNull('lead_quality')->orWhere('lead_quality', 'cold')->count(),
        ];

        return view('marketing.dashboard', compact('stats', 'incomingLeads', 'recentActivities', 'score', 'velocityData', 'qualityData'));
    }
}
