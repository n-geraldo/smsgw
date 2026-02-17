<?php

namespace App\Http\Controllers;

use App\Models\SmsJob;
use App\Models\SmsLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $sentToday = SmsJob::query()
            ->where('status', 'sent')
            ->whereDate('sent_at', $today)
            ->count();

        $failedToday = SmsJob::query()
            ->where('status', 'failed')
            ->whereDate('updated_at', $today)
            ->count();

        $pending = SmsJob::query()
            ->where('status', 'pending')
            ->count();

        $logs = SmsLog::query()
            ->with('job')
            ->latest('created_at')
            ->limit(20)
            ->get();

        return view('dashboard', compact('sentToday', 'failedToday', 'pending', 'logs'));
    }
}
