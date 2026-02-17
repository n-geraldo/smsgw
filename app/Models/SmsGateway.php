<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsGateway extends Model
{
    protected $fillable = [
        'name',
        'base_url',
        'endpoint_path',
        'auth_type',
        'username',
        'password',
        'token',
        'sender_id',
        'request_type',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'password' => 'encrypted',
        'token' => 'encrypted',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
}
