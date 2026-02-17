<?php

namespace App\Services;

use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function log(string $action, array $meta = []): void
    {
        AuditLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'meta' => $meta,
            'created_at' => Carbon::now(),
        ]);
    }
}
