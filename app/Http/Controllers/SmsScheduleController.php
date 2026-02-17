<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsScheduleRequest;
use App\Models\SmsSchedule;
use App\Models\SmsTemplate;
use App\Services\AuditLogger;

class SmsScheduleController extends Controller
{
    public function index()
    {
        $schedules = SmsSchedule::query()->with('template')->paginate(20);

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $templates = SmsTemplate::query()->orderBy('days_before')->get();

        return view('schedules.create', compact('templates'));
    }

    public function store(SmsScheduleRequest $request, AuditLogger $audit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $schedule = SmsSchedule::query()->create($data);

        $audit->log('sms_schedule_created', ['id' => $schedule->id]);

        return redirect()->route('schedules.index')->with('status', 'Schedule created.');
    }

    public function edit(SmsSchedule $schedule)
    {
        $templates = SmsTemplate::query()->orderBy('days_before')->get();

        return view('schedules.edit', compact('schedule', 'templates'));
    }

    public function update(SmsScheduleRequest $request, SmsSchedule $schedule, AuditLogger $audit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $schedule->update($data);

        $audit->log('sms_schedule_updated', ['id' => $schedule->id]);

        return redirect()->route('schedules.index')->with('status', 'Schedule updated.');
    }

    public function destroy(SmsSchedule $schedule, AuditLogger $audit)
    {
        $schedule->delete();

        $audit->log('sms_schedule_deleted', ['id' => $schedule->id]);

        return redirect()->route('schedules.index')->with('status', 'Schedule deleted.');
    }
}
