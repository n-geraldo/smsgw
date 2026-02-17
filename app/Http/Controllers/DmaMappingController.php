<?php

namespace App\Http\Controllers;

use App\Http\Requests\DmaMappingRequest;
use App\Services\DmaRepository;

class DmaMappingController extends Controller
{
    public function show(DmaRepository $dma)
    {
        $mapping = $dma->getMapping();

        return view('dma.mapping', [
            'mapping' => $mapping,
            'sample' => collect(),
        ]);
    }

    public function update(DmaMappingRequest $request, DmaRepository $dma)
    {
        $mapping = $dma->validateMapping($request->validated());
        $dma->saveMapping($mapping);

        return redirect()->route('dma.mapping')->with('status', 'DMA mapping saved.');
    }

    public function test(DmaMappingRequest $request, DmaRepository $dma)
    {
        $mapping = $dma->validateMapping($request->validated());
        $dma->saveMapping($mapping);

        $sample = $dma->testSample(10);

        if (isset($mapping['status_active_values']) && is_array($mapping['status_active_values'])) {
            $mapping['status_active_values'] = implode(',', $mapping['status_active_values']);
        }

        return view('dma.mapping', [
            'mapping' => $mapping,
            'sample' => $sample,
        ])->with('status', 'Test query completed.');
    }
}
