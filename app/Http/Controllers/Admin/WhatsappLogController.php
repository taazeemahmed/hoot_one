<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappLog;
use Illuminate\Http\Request;

class WhatsappLogController extends Controller
{
    public function index()
    {
        $logs = WhatsappLog::with('patient', 'order')
            ->latest()
            ->paginate(20);

        return view('admin.whatsapp_logs.index', compact('logs'));
    }
}
