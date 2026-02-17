<?php

namespace App\Http\Controllers;

use App\Services\DmaRepository;

class DmaConfigController extends Controller
{
    public function show()
    {
        $config = config('database.connections.dma');
        $masked = $config;
        $masked['password'] = $config['password'] ? str_repeat('*', 8) : '';

        return view('dma.config', compact('config', 'masked'));
    }

    public function test(DmaRepository $dma)
    {
        $ok = $dma->connectionHealthy();

        return back()->with('status', $ok ? 'DMA connection OK.' : 'DMA connection failed. Check .env settings.');
    }
}
