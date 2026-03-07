<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RepresentativeController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Representative\DashboardController as RepDashboardController;
use App\Http\Controllers\Representative\PatientController as RepPatientController;
use App\Http\Controllers\Representative\OrderController as RepOrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\WhatsappLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Dashboard redirection based on role
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        if ($role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'representative') {
            return redirect()->route('representative.dashboard');
        } elseif ($role === 'marketing_member') {
            return redirect()->route('marketing.dashboard');
        }
        return abort(403);
    })->name('dashboard');
});

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('representatives', RepresentativeController::class);
    Route::resource('medicines', MedicineController::class);
    Route::resource('patients', AdminPatientController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('marketing-members', App\Http\Controllers\Admin\MarketingMemberController::class);
    Route::get('/marketing-members/{user}/target', [App\Http\Controllers\Admin\MarketingMemberController::class, 'setTarget'])->name('marketing-members.set-target');
    Route::post('/marketing-members/{user}/target', [App\Http\Controllers\Admin\MarketingMemberController::class, 'storeTarget'])->name('marketing-members.store-target');

    // Lead Management (Company Direct + All Leads View)
    Route::resource('leads', App\Http\Controllers\Admin\LeadController::class);
    Route::post('/leads/{lead}/assign', [App\Http\Controllers\Admin\LeadController::class, 'assign'])->name('leads.assign');
    Route::post('/leads/{lead}/activity', [App\Http\Controllers\Admin\LeadController::class, 'storeActivity'])->name('leads.activity.store');

    // Media Library
    Route::get('/media', [App\Http\Controllers\Admin\MediaLibraryController::class, 'index'])->name('media.index');
    Route::post('/media', [App\Http\Controllers\Admin\MediaLibraryController::class, 'store'])->name('media.store');
    Route::delete('/media/{mediaFile}', [App\Http\Controllers\Admin\MediaLibraryController::class, 'destroy'])->name('media.destroy');

    // WhatsApp Campaigns
    Route::get('/campaigns', [App\Http\Controllers\Admin\CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [App\Http\Controllers\Admin\CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [App\Http\Controllers\Admin\CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [App\Http\Controllers\Admin\CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns/{campaign}/recipients', [App\Http\Controllers\Admin\CampaignController::class, 'addRecipients'])->name('campaigns.add-recipients');
    Route::delete('/campaigns/{campaign}/recipients/{recipient}', [App\Http\Controllers\Admin\CampaignController::class, 'removeRecipient'])->name('campaigns.remove-recipient');
    Route::post('/campaigns/{campaign}/send', [App\Http\Controllers\Admin\CampaignController::class, 'send'])->name('campaigns.send');
    Route::post('/campaigns/{campaign}/retry-failed', [App\Http\Controllers\Admin\CampaignController::class, 'retryFailed'])->name('campaigns.retry-failed');
    Route::get('/campaigns/{campaign}/progress', [App\Http\Controllers\Admin\CampaignController::class, 'progress'])->name('campaigns.progress');
    Route::get('/campaigns/{campaign}/logs', [App\Http\Controllers\Admin\CampaignController::class, 'logs'])->name('campaigns.logs');
    Route::delete('/campaigns/{campaign}', [App\Http\Controllers\Admin\CampaignController::class, 'destroy'])->name('campaigns.destroy');

    // Analytics & Reports
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/leads', [App\Http\Controllers\Admin\AnalyticsController::class, 'leads'])->name('analytics.leads');
    Route::get('/analytics/representatives', [App\Http\Controllers\Admin\AnalyticsController::class, 'representatives'])->name('analytics.representatives');
    Route::get('/analytics/marketing', [App\Http\Controllers\Admin\AnalyticsController::class, 'marketing'])->name('analytics.marketing');

    // Settings & Logs
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test', [SettingsController::class, 'test'])->name('settings.test');
    Route::get('/whatsapp-logs', [WhatsappLogController::class, 'index'])->name('whatsapp_logs.index');
});

// Representative Routes
Route::middleware(['auth', 'role:representative'])->prefix('portal')->name('representative.')->group(function () {
    Route::get('/dashboard', [RepDashboardController::class, 'index'])->name('dashboard');

    Route::resource('patients', RepPatientController::class);
    Route::resource('orders', RepOrderController::class);

    // Lead Management
    Route::get('/leads', [App\Http\Controllers\Representative\LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/{lead}/edit', [App\Http\Controllers\Representative\LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [App\Http\Controllers\Representative\LeadController::class, 'update'])->name('leads.update');
    Route::post('/leads/{lead}/activity', [App\Http\Controllers\Representative\LeadController::class, 'storeActivity'])->name('leads.activity.store');
});

// Marketing Routes
Route::middleware(['auth', 'role:marketing_member'])->prefix('marketing')->name('marketing.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Marketing\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('leads', App\Http\Controllers\Marketing\LeadController::class);
    Route::post('/leads/{lead}/assign', [App\Http\Controllers\Marketing\LeadController::class, 'assign'])->name('leads.assign');
});

// Shared Activity Routes
Route::post('/lead-activities', [App\Http\Controllers\LeadActivityController::class, 'store'])->name('lead_activities.store')->middleware('auth');

require __DIR__.'/auth.php';
