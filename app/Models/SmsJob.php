<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsJob extends Model
{
    protected $fillable = [
        'customer_username',
        'phone',
        'template_id',
        'scheduled_for',
        'expiration_date',
        'status',
        'attempt_count',
        'last_error',
        'provider_message_id',
        'sent_at',
    ];

    protected $casts = [
        'scheduled_for' => 'date',
        'expiration_date' => 'date',
        'attempt_count' => 'integer',
        'sent_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SmsLog::class, 'sms_job_id');
    }
}
