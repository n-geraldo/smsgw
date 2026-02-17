<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'trigger_type',
        'days_before',
        'message_body',
        'is_active',
    ];

    protected $casts = [
        'days_before' => 'integer',
        'is_active' => 'boolean',
    ];
}
