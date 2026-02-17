<?php

namespace App\Http\Controllers;

use App\Models\SmsJob;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsJob::query()->with('template')->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', (string) $request->string('status'));
        }

        if ($request->filled('template_id')) {
            $query->where('template_id', $request->integer('template_id'));
        }

        if ($request->filled('username')) {
            $query->where('customer_username', 'like', '%'.(string) $request->string('username').'%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%'.(string) $request->string('phone').'%');
        }

        if ($request->filled('from')) {
            $query->whereDate('scheduled_for', '>=', (string) $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('scheduled_for', '<=', (string) $request->string('to'));
        }

        $jobs = $query->paginate(25)->withQueryString();
        $templates = SmsTemplate::query()->orderBy('days_before')->get();

        return view('sms.monitor', compact('jobs', 'templates'));
    }

    public function show(SmsJob $job)
    {
        $job->load('template', 'logs');

        return view('sms.show', compact('job'));
    }
}
