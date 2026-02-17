<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsTemplateRequest;
use App\Models\SmsTemplate;
use App\Services\AuditLogger;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $templates = SmsTemplate::query()->orderBy('days_before')->paginate(20);

        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(SmsTemplateRequest $request, AuditLogger $audit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $template = SmsTemplate::query()->create($data);

        $audit->log('sms_template_created', ['id' => $template->id]);

        return redirect()->route('templates.index')->with('status', 'Template created.');
    }

    public function edit(SmsTemplate $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function update(SmsTemplateRequest $request, SmsTemplate $template, AuditLogger $audit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $template->update($data);

        $audit->log('sms_template_updated', ['id' => $template->id]);

        return redirect()->route('templates.index')->with('status', 'Template updated.');
    }

    public function destroy(SmsTemplate $template, AuditLogger $audit)
    {
        $template->delete();

        $audit->log('sms_template_deleted', ['id' => $template->id]);

        return redirect()->route('templates.index')->with('status', 'Template deleted.');
    }
}
