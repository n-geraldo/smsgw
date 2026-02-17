<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsSchedule extends Model
{
    protected $fillable = [
        'template_id',
        'run_hour',
        'timezone',
        'is_active',
        'last_run_at',
    ];

    protected $casts = [
        'run_hour' => 'integer',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }
}
