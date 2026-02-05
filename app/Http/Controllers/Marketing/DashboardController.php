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
        $userId = Auth::id();
        
        // Core Stats - ONLY for this marketing member's leads
        $stats = [
            'new_leads' => Patient::where('assigned_by', $userId)->where('lead_status', 'new')->count(),
            'assigned_today' => Patient::where('assigned_by', $userId)->whereDate('assigned_at', today())->count(),
            'hot_leads_unassigned' => Patient::where('assigned_by', $userId)
                ->where('lead_quality', 'hot')
                ->whereNull('representative_id')
                ->count(),
            'total_leads' => Patient::where('assigned_by', $userId)->count(),
            'converted_leads' => Patient::where('assigned_by', $userId)->where('lead_status', 'converted')->count(),
        ];

        // Leads needing assignment - ONLY this member's leads
        $incomingLeads = Patient::where('assigned_by', $userId)
            ->where('lead_status', 'new')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // My recent activities
        $recentActivities = LeadActivity::where('user_id', $userId)
            ->with(['patient'])
            ->latest()
            ->take(5)
            ->get();

        // Targets & Score
        $currentMonth = now()->format('Y-m');
        $target = \App\Models\MarketingTarget::where('user_id', $userId)
            ->where('month_year', $currentMonth)
            ->first();

        $myLeadsAddedCount = Patient::where('assigned_by', $userId)
            ->whereMonth('assigned_at', now()->month)
            ->whereYear('assigned_at', now()->year)
            ->count();
        
        $myLeadsConvertedCount = Patient::where('assigned_by', $userId)
            ->where('lead_status', 'converted')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        
        $score = [
            'leads_assigned' => $myLeadsAddedCount,
            'leads_converted' => $myLeadsConvertedCount,
            'target_assigned' => $target ? $target->leads_assigned_target : 0,
            'percentage' => ($target && $target->leads_assigned_target > 0) ? round(($myLeadsAddedCount / $target->leads_assigned_target) * 100) : 0,
            'conversion_rate' => $myLeadsAddedCount > 0 ? round(($myLeadsConvertedCount / $myLeadsAddedCount) * 100) : 0,
        ];

        // Chart Data: Assignment Velocity (Last 7 Days) - ONLY this member's leads
        $velocityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Patient::where('assigned_by', $userId)->whereDate('assigned_at', $date)->count();
            $velocityData[] = [
                'day' => $date->format('D'),
                'count' => $count
            ];
        }

        // Chart Data: Lead Quality Distribution - ONLY this member's leads
        $qualityData = [
            'hot' => Patient::where('assigned_by', $userId)->where('lead_quality', 'hot')->count(),
            'warm' => Patient::where('assigned_by', $userId)->where('lead_quality', 'warm')->count(),
            'cold' => Patient::where('assigned_by', $userId)->where(function($q) {
                $q->whereNull('lead_quality')->orWhere('lead_quality', 'cold');
            })->count(),
        ];

        return view('marketing.dashboard', compact('stats', 'incomingLeads', 'recentActivities', 'score', 'velocityData', 'qualityData'));
    }
}
