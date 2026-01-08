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
        if (Auth::user()->role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'representative') {
            return redirect()->route('representative.dashboard');
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

    // Settings & Logs
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
});

require __DIR__.'/auth.php';
