<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsGatewayRequest;
use App\Models\SmsGateway;
use App\Services\AuditLogger;

class SmsGatewayController extends Controller
{
    public function index()
    {
        $gateways = SmsGateway::query()->orderByDesc('is_default')->paginate(20);

        return view('gateways.index', compact('gateways'));
    }

    public function create()
    {
        return view('gateways.create');
    }

    public function store(SmsGatewayRequest $request, AuditLogger $audit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            $data['is_active'] = true;
        }

        $gateway = SmsGateway::query()->create($data);

        if ($gateway->is_default) {
            SmsGateway::query()
                ->where('id', '!=', $gateway->id)
                ->update(['is_default' => false]);
        }

        $audit->log('sms_gateway_created', ['id' => $gateway->id]);

        return redirect()->route('gateways.index')->with('status', 'Gateway created.');
    }

    public function edit(SmsGateway $gateway)
    {
        return view('gateways.edit', compact('gateway'));
    }

    public function update(SmsGatewayRequest $request, SmsGateway $gateway, AuditLogger $audit)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            $data['is_active'] = true;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (empty($data['token'])) {
            unset($data['token']);
        }

        $gateway->update($data);

        if ($gateway->is_default) {
            SmsGateway::query()
                ->where('id', '!=', $gateway->id)
                ->update(['is_default' => false]);
        }

        $audit->log('sms_gateway_updated', ['id' => $gateway->id]);

        return redirect()->route('gateways.index')->with('status', 'Gateway updated.');
    }

    public function destroy(SmsGateway $gateway, AuditLogger $audit)
    {
        $gateway->delete();

        $audit->log('sms_gateway_deleted', ['id' => $gateway->id]);

        return redirect()->route('gateways.index')->with('status', 'Gateway deleted.');
    }
}
